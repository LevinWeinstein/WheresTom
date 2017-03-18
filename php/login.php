<?php

session_start();
require 'dbcon.php';

if ($_POST['email'] != null) {
	$email = $_POST['email'];

	$stmt = $db->prepare("SELECT password, id FROM users WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->bind_result($password, $userID);
	$stmt->store_result();
	$stmt->fetch();
	$stmt->close();
	if ($_POST['password'] === $password) {
		echo 'yes';
		$_SESSION['userid'] = $userID;
	} else {
		echo 'no';
	}
}

?>