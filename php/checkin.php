<?php

session_start();
require 'dbcon.php';

if ($_POST['checkin'] != null){
	$time = time();
	$type = 0;
	$x = $_POST['x'];
	$y = $_POST['y'];
	$userID = $_SESSION['userid'];
	$stmt = $db->prepare("INSERT INTO punches (`userid`, `time`, `xlocation`, `ylocation`) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("iidd", $userID, $time, $x, $y);
	$stmt->execute();
	$stmt->close();
}

?>