<?php

require_once "db.php";

$db = new ChatDB();

$latest_message_id = (int)$_GET["after"] ?? 0;
$messages = $db->fetch_messages_after($latest_message_id);

header("Content-Type: application/json");
echo json_encode($messages);
