<?php
include_once('db.php');

$STATUS_DEFAULT = 0;			//default (show request student name) --> 1
$STATUS_WAIT_STUDENT_NAME = 1;	//wait for a student name 
								// (if false show request student name)
								// (if true show request national) --> 2
$STATUS_WAIT_NATIONAL = 2;		//wait for a national id card
								// (if false show request national)
								// (if true show main menu) --> 3
$STATUS_WAIT_MAIN_QUEST = 3;	//wait for a main answer
								// (if MAIN_INFO show student's info, show after main) --> 5
								// (if MAIN_PICTURE show student's picture, show after main) --> 5
								// (if MAIN_GRADE show student's current GPA, show term GPA question) --> 4
								// (if MAIN_TABLE show student's class table, show after main) --> 5
								// (if MAIN_NEW_STUDENT show request student name) --> 1
$STATUS_WAIT_TERM_QUEST = 4;	//wait for a term answer
								// (if QUEST_NO show after main) --> 5
								// (if QUEST_YES show term GPA, show after main) --> 5
$STATUS_AFTER_MAIN = 5;			//after select main menu (show yes/no question)
								// (if QUEST_NO show thank you) --> 6
								// (if QUEST_YES show main menu) --> 3
$STATUS_SHOW_MAIN = 6;			//when input text or others will show show main menu --> 3

$MAIN_INFO = 1;					//info of student
$MAIN_PICTURE = 2;				//pickture of student
$MAIN_GRADE = 3;				//grade of student
$MAIN_TABLE = 4;				//class table of student
$MAIN_NEW_STUDENT = 5;			//choose new student

$QUEST_YES = 1;					//say yes
$QUEST_NO = 0;					//say no

$REQUEST_STUDENT_NAME = "คุณต้องการทราบข้อมูลของนิสิตชื่ออะไรครับ?";
$REQUEST_FALSE_STUDENT = "ไม่พบชื่อนี้ในระบบ";
$REQUEST_NATIONAL_ID = "กรุณาพิมพ์เลขบัตรประชาชนผู้ปกครอง";

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// query results and put it back

			//query state
			$userId = $event['source']['userId'];
			$userState = checkState($userId);
			if ($userState == $STATUS_DEFAULT) {
				$text = $REQUEST_STUDENT_NAME;
				changeState($userId, $STATUS_WAIT_STUDENT_NAME);
			} else if ($userState == $STATUS_WAIT_STUDENT_NAME) {
				$countStudent = count(checkStudentName($text));
				if ($countStudent != 0) {
					$text = $REQUEST_NATIONAL_ID;
					changeState($userId, $STATUS_WAIT_STUDENT_NAME);
				} else {
					$text = $REQUEST_FALSE_STUDENT;
				}
			}

			
			// *****************

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			sendMessage($post);
			
		} else if ($event['type'] == 'follow') {
			createUser($event['source']['userId']);
		}
	} 
}

function sendMessage($post){
	$access_token = '2LdmytXWuZ/QWRbEQ0LX244R2+sxD73YSOWiqkvS9fhQEPnhWBeHgTwBKBRkJcZOTbPDygLZdqp7theK2GlMJexM7dGlZ6iEKydgAIZncUrNtKSzzObwhR4q55k89TIxBJaFsRKjLISkLT+0uhi2rgdB04t89/1O/w1cDnyilFU=';

	$url = 'https://api.line.me/v2/bot/message/reply';
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result . "\r\n";
}

function changeState($userId, $userState) {
	$objConn = new db();
	$objConn->connect();

	//update user state
	$objConn->query("UPDATE `chatbotUser` SET `userState` = '" . $userState . "' WHERE `userId` = '" . $userId . "'");
}

//function to check student firstname and surname
function checkStudentName($text) {
	//create connection
	$objConn = new db();
	//let connect database
	$objConn->connect();
	//cut left and right space
	$text = trim($text, ' ');
	//split firstname and lastname
	$doubleName = explode(' ', $text);
	//check if can cut doubleName == 2 (if not == 1)
	if (count($doubleName) == 1) {
		$objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentFirstName` = '" . $doubleName[0] . "' OR `studentLastName` = '" . $doubleName[0] . "'");
	} else {
		$objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentFirstName` = '" . $doubleName[0] . "' AND `studentLastName` = '" . $doubleName[1] . "'");
	}
	$resultArray = [];
	$count = 0;
	foreach ($objConn->results->fetchall() as $temp) {
		 $resultArray[$count] = $temp['studentId'];
		 $count += 1;
	}
	return $resultArray;
}

function checkState($userId) {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT `userState` FROM `chatbotUser` WHERE `userId` = '" . $userId . "'");
	// $objConn->close();
	foreach ($objConn->results->fetchall() as $temp) {
		return $temp['userState'];
	}
}

function createUser($userId) {
	$objConn = new db();
	$objConn->connect();

	//insert and get boolean return true or false
	$isInsert = $objConn->query("INSERT INTO `chatbotUser` (`userId`) VALUES ('" . $userId . "')");
	// if ($isInsert == false) {
	// 	//set user's state to default
	// 	$objConn->query("UPDATE `chatbotUser` SET `userState` = '0' WHERE `userId` = '" . $userId . "'");
	// }

	// $objConn->close();
}
?>
