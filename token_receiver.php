<?php

	$host = "localhost";
	$db = "policies";
	$user = "root";
	$pass = "";

	// Read token from POST
	$email = $_POST['email'] ?? '';
	$token = $_POST['token'] ?? '';

	if (empty($email)) {
    	echo json_encode(["status" => "error", "message" => "Email is missing."]);
    	exit;
    }
	else if (empty($token)) {
    	echo json_encode(["status" => "error", "message" => "Token is missing."]);
    	exit;
	}

	// DB connection
	$conn = new mysqli($host, $user, $pass, $db);

	if ($conn->connect_error) {
    	die(json_encode(["status" => "error", "message" => "Connection failed."]));
	}

	// Save token
	$stmt = $conn->prepare("UPDATE users SET fcm_id = ? WHERE email_address = ?");
	$stmt->bind_param("ss", $token, $email);

	if ($stmt->execute()) {
    	if ($stmt->affected_rows > 0) {
    		echo json_encode(["status" => "success", "message" => "Token saved."]);
        }
    	else {
        	echo json_encode(["status" => "error", "message" => "Email not found."]);
        }
	} 
    else {
    	echo json_encode(["status" => "error", "message" => "Failed to save token."]);
	}

	$stmt->close();
	$conn->close();

?>