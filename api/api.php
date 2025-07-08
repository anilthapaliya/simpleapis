<?php
// Allow cross-origin (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Connect to DB
$host = "localhost";
$dbname = "policies";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Helper: generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Read input JSON
$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {

    // SIGNUP
    case 'signup':
        $name = $conn->real_escape_string($data['name'] ?? '');
		$address = $conn->real_escape_string($data['address'] ?? '');
        $email = $conn->real_escape_string($data['email'] ?? '');
        $password = password_hash($data['password'] ?? '', PASSWORD_BCRYPT);

        if (!$name || !$email || !$password || !$address) {
            echo json_encode(["code" => 210, "message" => "Missing fields"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (full_name, address, email_address, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $address, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["code" => 200, "message" => "User successfuly registered"]);
        } else {
            echo json_encode(["code" => 201, "message" => "Email already exists"]);
        }
        break;

    // LOGIN
    case 'login':
        $email = $conn->real_escape_string($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $stmt = $conn->prepare("SELECT user_id, full_name, address, password FROM users WHERE email_address = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $token = generateToken();
            $conn->query("UPDATE users SET token = '$token' WHERE user_id = " . $user['user_id']);
            echo json_encode([
                "code" => 200,
                "message" => "Login successful",
                "user" => [
                    "user_id" => $user['user_id'],
                    "full_name" => $user['full_name'],
                    "email_address" => $email,
                	"address" => $user['address'],
                	"token" => $token,
                ]
            ]);
        } else {
            echo json_encode(["code" => 201, "message" => "Invalid credentials"]);
        }
        break;

    // GET USER INFO
    case 'get_all_songs':
        $token = $conn->real_escape_string($data['token'] ?? '');

		$stmt = $conn->prepare("SELECT user_id FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

		if ($user) {
        	$stmt = $conn->prepare("SELECT title, artist, genre, cover_art, file_path FROM songs");
        	$stmt->execute();
        	$result = $stmt->get_result();
        	$songs = [];
    		while ($row = $result->fetch_assoc()) {
        		$songs[] = $row;
    		}

        	if ($songs) {          
            	echo json_encode([
                	"code" => 200,
                	"message" => "Songs fetched",
                	"songs" => $songs
            	]);
        	} else {
            	echo json_encode(["code" => 201, "message" => "No songs", "songs" => []]);
        	}
        }
		else {
        	echo json_encode(["code" => 210, "message" => "Invalid token"]);
        }

        break;

    default:
        echo json_encode(["code" => -100, "message" => "Invalid action."]);
}

?>
