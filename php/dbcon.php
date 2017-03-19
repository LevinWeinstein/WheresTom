<?php

$db = mysqli_connect('localhost', 'root', 'password', 'wherestom');
if (developmentEnvironment()){
	echo 'hi';
	$db = mysqli_connect('wherestom:8889', 'root', 'root', 'wherestom');
}else{
	echo 'no';
}

function developmentEnvironment() {
	if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', "::1"))) {
		return false;
	}
	return true;
}

?>