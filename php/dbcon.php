<?php

$db = mysqli_connect('localhost', 'kash_wherestom', 'Wherestom10', 'kash_wherestom');
if (developmentEnvironment()) {
	$db = mysqli_connect('wherestom:8889', 'root', 'root', 'wherestom');
}

function developmentEnvironment() {
	if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', "::1"))) {
		return false;
	}
	return true;
}

?>