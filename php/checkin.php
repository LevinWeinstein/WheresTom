<?php

session_start();
require 'dbcon.php';

if ($_POST['checkin'] != null){
	$time = time();
	$type = 0;
	$userID = $_SESSION['userid'];
	$stmt = $db->prepare("INSERT INTO punches (`userid`, `time`) VALUES (?, ?)");
	$stmt->bind_param("ii", $userID, $time);
	$stmt->execute();
	$stmt->close();
}

?>