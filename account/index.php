<?php

session_start();
include_once '../php/dbcon.php';
if (!isset($_SESSION['userid'])){
	header("Location: /");
}

$userID = $_SESSION['userid'];
$stmt = $db->prepare("SELECT password, id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($password, $userID);
$stmt->store_result();
$stmt->fetch();
$stmt->close();

$id = $_SESSION['userid'];
$stmt = $db->prepare("SELECT COUNT(*) FROM punches WHERE userid = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($punches);
$stmt->store_result();
$stmt->fetch();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Account</title>
	<link rel="stylesheet" href="/css/fa/css/fa.min.css">
	<link rel="stylesheet" href="/css/reset.css" type="text/css">
	<link rel="stylesheet" href="/css/account.css" type="text/css">
</head>
<body>
<div class="main-wrapper">
	<div class="check-box-wrapper">
		<a href="/"><img src="/img/wherestom-logo.png" alt=""></a>
		<div class="check-box">
			<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw margin-bottom"></i>
			<p>Locating you...</p>
			<button><?php if ($punches % 2 == 0) echo "Punch in"; else echo "Punch out"; ?></button>
		</div>
	</div>
</div>

<script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>

<script>
	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else {
			x.innerHTML = "Geolocation is not supported by this browser.";
		}
	}
	function showPosition(position) {
		$('.check-box i, .check-box p').css('display', 'none');
		$('.check-box button').data('x', position.coords.latitude).data('y', position.coords.longitude).show();
	}
	getLocation();
</script>

<script src="/js/account.js"></script>
</body>
</html>