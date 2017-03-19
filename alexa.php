<?php

$json = file_get_contents("php://input");
$json = json_decode($json, true);
date_default_timezone_set('America/Los_Angeles');
header('Content-Type: application/json;charset=UTF-8');

$finalOutput = 'Okay, ask me about any of your employees';

$intentName = $json['request']['intent']['name'];
$employeeName = $json['request']['intent']['slots']['name']['value'];
$db = mysqli_connect('localhost', 'kash_wherestom', 'Wherestom10', 'kash_wherestom');

switch ($intentName) {
	case "GetEmployeeLocation":

		$stmt = $db->prepare("SELECT id FROM users WHERE firstname = ? OR lastname = ?");
		$stmt->bind_param("ss", $employeeName, $employeeName);
		$stmt->execute();
		$stmt->bind_result($userID);
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();

		if ($userID != 0) {
			$stmt = $db->prepare("SELECT COUNT(*) FROM punches WHERE userid = ?");
			$stmt->bind_param("i", $userID);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->store_result();
			$stmt->fetch();
			$stmt->close();

			$stmt = $db->prepare("SELECT `time`, xlocation, ylocation FROM punches WHERE userid = ? ORDER BY `time` DESC LIMIT 1");
			$stmt->bind_param("i", $userID);
			$stmt->execute();
			$stmt->bind_result($time, $x, $y);
			$stmt->store_result();
			$stmt->fetch();
			$stmt->close();

			$response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $x . "," . $y . "&sensor=true");
			$response = json_decode($response, true);
			$city = $response["results"][0]["address_components"][2]["long_name"];
			$state = $response["results"][0]["address_components"][4]["long_name"];
			$date = date("F j, Y", $time);
			$time = date("g:i A", $time);

			if ($count % 2 == 0) {
				$finalOutput = $employeeName . " last checked out from " . $city . ", " . $state . " on " . $date . " at " . $time;
			} else {
				$finalOutput = $employeeName . " checked in from " . $city . ", " . $state . " on " . $date . " at " . $time;
			}
		} else {
			if ($employeeName == null) {
				$finalOutput = $employeeName . " doesn't work for us.";
			} else {
				$finalOutput = "I'm sorry, I don't understand that question. Ask me anything about your employees.";
			}
		}
		break;
	case "GetEmployeeStatus":
		$stmt = $db->prepare("SELECT id FROM users WHERE firstname = ? OR lastname = ?");
		$stmt->bind_param("ss", $employeeName, $employeeName);
		$stmt->execute();
		$stmt->bind_result($userID);
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();

		if ($userID != 0) {
			$stmt = $db->prepare("SELECT COUNT(*) FROM punches WHERE userid = ?");
			$stmt->bind_param("i", $userID);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->store_result();
			$stmt->fetch();
			$stmt->close();
			if ($count % 2 == 0) {
				$finalOutput = $employeeName . " is not at work.";
			} else {
				$finalOutput = $employeeName . " is currently at work.";
			}
		} else {
			$finalOutput = "That person doesn't work for us.";
		}
		break;
	case "GetEmployeeSchedule":
		$json = file_get_contents("schedule.json");
		$json = json_decode($json, true);
		$date = date("Y-m-d", time());
		$empArray = array();
		$stringBuilder = "All your employees are here.";
		foreach ($json as $i) {
			$emp = $i['employee'];
			$schedule = $i['schedule'][$date];

			$beginTime = strtotime($date . ' ' . explode("-", $schedule)[0]);
			$beginTime = strtotime("9AM", $beginTime);
			$endTime = $beginTime + 28800;
			if ($beginTime < time() && $endTime > time()) {
				$stmt = $db->prepare("SELECT COUNT(*) FROM punches WHERE userid = ?");
				$stmt->bind_param("i", $emp);
				$stmt->execute();
				$stmt->bind_result($count);
				$stmt->store_result();
				$stmt->fetch();
				$stmt->close();

				$stmt = $db->prepare("SELECT firstname FROM users WHERE id = ?");
				$stmt->bind_param("i", $emp);
				$stmt->execute();
				$stmt->bind_result($firstname);
				$stmt->store_result();
				$stmt->fetch();
				$stmt->close();

				if ($count % 2 == 0) {
					array_push($empArray, $firstname);
				}
			}
		}
		if (count($empArray) == 1) {
			$stringBuilder = $empArray[0] . " is the only employee not here.";
		} else if (count($empArray) >= 1) {
			$stringBuilder = null;
			foreach ($empArray as $val) {
				$stringBuilder .= $val . ', ';
			}
			$stringBuilder .= "are not here";
		}

		$finalOutput = $stringBuilder;
		break;
	case "GetEmployeePhone":
		$employeeName = str_replace("'s", "", $employeeName);
		$stmt = $db->prepare("SELECT phone FROM users WHERE firstname = ? OR lastname = ?");
		$stmt->bind_param("ss", $employeeName, $employeeName);
		$stmt->execute();
		$stmt->bind_result($phone);
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();

		$phone = implode(' ', str_split($phone));

		$finalOutput = $employeeName . "'s phone number is " . $phone;
		break;
	default:
		break;
}


$text = '{
    "version" : "1.0",
    "response" : {
        "outputSpeech" : {
            "type" : "PlainText",
            "text" : "' . $finalOutput . '"
        },
        "shouldEndSession" : false
    }
}';

header('Content-Length: ' . strlen($text));
echo $text;
