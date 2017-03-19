<?php
$data = file_get_contents("php://input");
header('Content-Type: application/json;charset=UTF-8');
$text = '{
    "version" : "1.0",
    "response" : {
        "outputSpeech" : {
            "type" : "PlainText",
            "text" : "Test Response"
        },
        "shouldEndSession" : false
    }
}';
header('Content-Length: ' . strlen($text));
echo $text;
?>
