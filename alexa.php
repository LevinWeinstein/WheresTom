<?php
$data = file_get_contents("php://input");
header('Content-Type: application/json;charset=UTF-8');

//$db = mysqli_connect('localhost', 'kash_wherestom', 'Wherestom10', 'kash_wherestom');
//$stmt = $db->prepare("UPDATE users SET firstname = 'hello'");
//$stmt->execute();
//$stmt->close();


$testOutput = 'It works. It actually works. Good job Kash.';
//if ($result){
//	$testOutput = 'it worked';
//}else{
//	$testOutput = 'it failed';
//}
//$stmt = $db->prepare("UPDATE punched SET `time` = ? WHERE userid = ?");
//$time = 50;
//$userID = 1;
//$stmt->bind_param("ii", $time, $userID);
//$stmt->execute();
//$stmt->close();
$text = '{
    "version" : "1.0",
    "response" : {
        "outputSpeech" : {
            "type" : "PlainText",
            "text" : "' . $testOutput . '"
        },
        "shouldEndSession" : false
    }
}';
header('Content-Length: ' . strlen($text));
echo $text;
