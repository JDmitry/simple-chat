<?php

require_once "db.php";

if ("POST" == $_SERVER["REQUEST_METHOD"]) {

	$sender = $_POST["sender"];
	$text = $_POST["text"];

	$db = new ChatDB();
	
	$message = $db->insert_message($sender, $text);
	
	header("Content-Type: application/json");
	http_response_code(201);
	echo json_encode($message);
}
