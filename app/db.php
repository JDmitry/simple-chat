<?php

final class ChatDB {

	private $host;
	private $user;
	private $password;
	private $database;
		
	function __construct($host = "mysql") {
		$this->host = $host;
		$this->user = getenv("MYSQL_USER");
		$this->password = getenv("MYSQL_PASSWORD");
		$this->database = getenv("MYSQL_DATABASE");
	}
	
	private function connect(): mysqli {
		$conn = new mysqli(
			$this->host,
			$this->user,
			$this->password,
			$this->database
		);
		if ($conn->connect_error) {
	    	throw new Exception("Connection failed: " . $conn->connect_error);
		}
		$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
		$conn->set_charset("utf8");
		return $conn;
	}

	function insert_message($sender, $text): object {
		$conn = $this->connect();
		$sql = "INSERT INTO message (sender, text) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $sender, $text);
		if (!$stmt->execute()) {
			throw new Exception("Insert failed: " . $conn->error);
		}
		return (object)[
			"id" => $conn->insert_id,
			"sender" => $sender,
			"text" => $text,
		];
	}
	
	function fetch_messages_after(int $message_id): array {
		$conn = $this->connect();
		$sql_prefix = "SELECT id, sender, text FROM message";
		$stmt = $conn->prepare($sql_prefix . " WHERE id > ?");
		$stmt->bind_param("i", $message_id);
		if (!$stmt->execute()) {
			throw new Exception("Fetch failed: " . $conn->error);
		}
		$messages = array();
		$result = $stmt->get_result();
		while($row = $result->fetch_object()) {
			$messages[] = $row;
    	}
		return $messages;
	}
}
