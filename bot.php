<?php
include_once('db.php');
include_once('BotMessage.php');

letStart();

function letStart() {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	// Get POST body content
	$content = file_get_contents('php://input');
	// Parse JSON
	$events = json_decode($content, true);
	// Validate parsed JSON data
	if (!is_null($events['events'])) {
		createUser($events['events'][0]['source']['userId']);
		// Loop through each event
		foreach ($events['events'] as $event) {
			// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];

				$replyText1 = '';
				$replyText2 = '';
				$replyImageUrl1 = '';

				// query results and put it back

				//query state
				$userId = $event['source']['userId'];
				$userState = checkState($userId);
				if ($userState == BotMessage::$STATUS_DEFAULT) {
					$replyText1 = $botMessage->REQUEST_STUDENT_NAME;
					changeState($userId, BotMessage::$STATUS_WAIT_STUDENT_NAME);
				} else if ($userState == BotMessage::$STATUS_WAIT_STUDENT_NAME) {
					$resultStudentArray = checkStudentName($text);
					$countStudent = count($resultStudentArray);
					if ($countStudent != 0) {
						$studentId =  implode("&", $resultStudentArray);
						setUserStudentId($userId, $studentId);
						changeState($userId, BotMessage::$STATUS_WAIT_NATIONAL);
						$replyText1 = $botMessage->REQUEST_NATIONAL_ID;
					} else {
						$replyText1 = $botMessage->REQUEST_FALSE_STUDENT;
					}
				} else if ($userState == BotMessage::$STATUS_WAIT_NATIONAL) {
					$studentId = getUserStudentId($userId);
					$studentIdArray = explode('&', $studentId);
					$correctStudent = '';
					foreach ($studentIdArray as $stdId) {
						$correctStudent = checkNationalId($text, $stdId);
						if ($correctStudent != '') {
							break;
						}
					}
					if ($correctStudent != '') {
						setUserStudentId($userId, $correctStudent);
						$replyText1 = getMainMenu($userId);
						changeState($userId, BotMessage::$STATUS_WAIT_MAIN_QUEST);
					} else {
						$replyText1 = $botMessage->REQUEST_FALSE_NATIONAL_ID;
						$replyText2 = $botMessage->REQUEST_STUDENT_NAME;
						changeState($userId, BotMessage::$STATUS_WAIT_STUDENT_NAME);
					}
				} else if ($userState == BotMessage::$STATUS_WAIT_MAIN_QUEST) {
					if (strpos($botMessage->REQUEST_MAIN_INFO, $text)) {
						$studentId = getUserStudentId($userId);
						$studentDetail = getStudent($studentId);
						$replyText1 = $botMessage->REQUEST_INFO_NAME . $studentDetail['studentPreName'] . $studentDetail['studentFirstName'] . ' ' . $studentDetail['studentLastName'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_STUDENT_ID . $studentDetail['studentId'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_BIRTHDATE . $studentDetail['studentBirthDate'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_FACULTY . $studentDetail['studentFaculty'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_BRANCH . $studentDetail['studentBranch'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_LEVEL . $studentDetail['studentLevel'] . "\n";
						$replyText1 .= $botMessage->REQUEST_INFO_STATUS . $studentDetail['studentStatus'];

						$replyText2 = getAfterMain();
						changeState($userId, BotMessage::$STATUS_AFTER_MAIN);

					} else if (stripos($botMessage->REQUEST_MAIN_PICTURE, $text)) {
						$studentId = getUserStudentId($userId);
						$studentDetail = getStudent($studentId);
						$replyImageUrl1 = BotMessage::$HOST . $studentDetail['studentPictureUrl'];
						$replyText2 = getAfterMain();
						changeState($userId, BotMessage::$STATUS_AFTER_MAIN);
					} else if (stripos($botMessage->REQUEST_MAIN_GRADE, $text)) {
						$studentId = getUserStudentId($userId);
						$studentDetail = getStudent($studentId);
						$allGrade = getGrade($studentId);
						$gpa = 0.00;
						foreach ($allGrade as $grade) {
							$gpa += (double)$grade['gradePoint'];
						}
						$gpa /= count($allGrade);
						$replyText1 = $botMessage->REQUEST_GRADE . round($gpa, 2);
						foreach ($allGrade as $grade) {
							$replyText1 .= "\n" . $botMessage->REQUEST_GRADE_TERM . $grade['gradeTerm'] . $botMessage->REQUEST_GRADE_SUM . $grade['gradePoint'];
						}

						$replyText2 = getAfterMain();
						changeState($userId, BotMessage::$STATUS_AFTER_MAIN);

					} else if (stripos($botMessage->REQUEST_MAIN_TABLE, $text)) {
						$studentId = getUserStudentId($userId);
						$studentDetail = getStudent($studentId);
						$replyImageUrl1 = BotMessage::$HOST . $studentDetail['studentTableUrl'];
						$replyText2 = getAfterMain();
						changeState($userId, BotMessage::$STATUS_AFTER_MAIN);
					} else if (stripos($botMessage->REQUEST_MAIN_NEW_STUDENT, $text)) {
						$replyText1 = $botMessage->REQUEST_STUDENT_NAME;
						changeState($userId, BotMessage::$STATUS_WAIT_STUDENT_NAME);
					} else {
						$replyText1 = $botMessage->REQUEST_FALSE_MAIN_MENU;
						$replyText2 = getMainMenu($userId);
					}
				} else if ($userState == BotMessage::$STATUS_AFTER_MAIN) {
					if (stripos($botMessage->REQUEST_CHOICE_YES, $text)) {
						$replyText1 = getMainMenu($userId);
						changeState($userId, BotMessage::$STATUS_WAIT_MAIN_QUEST);
					} else if (stripos($botMessage->REQUEST_CHOICE_NO, $text)) {
						$replyText1 = $botMessage->REQUEST_THANK_YOU;
						changeState($userId, BotMessage::$STATUS_SHOW_MAIN);
					} else {
						$replyText1 = $botMessage->REQUEST_FALSE_MAIN_MENU;
						$replyText2 = getAfterMain();
					}
				} else if ($userState == BotMessage::$STATUS_SHOW_MAIN) {
					$replyText1 = getMainMenu($userId);
					changeState($userId, BotMessage::$STATUS_WAIT_MAIN_QUEST);
				}

				
				// *****************

				// Build message to reply back
				$allMessage = array();

				if ($replyImageUrl1 != '') {
					$messages = [
						'type' => 'image',
						'originalContentUrl' => $replyImageUrl1,
    					'previewImageUrl' => $replyImageUrl1
					];
					$allMessage[] = $messages;
				}

				if ($replyText1 != '') {
					$messages = [
						'type' => 'text',
						'text' => $replyText1
					];
					$allMessage[] = $messages;
				}

				if ($replyText2 != '') {
					$messages = [
						'type' => 'text',
						'text' => $replyText2
					];
					$allMessage[] = $messages;
				}

				// Make a POST Request to Messaging API to reply to sender
				$data = [
					'replyToken' => $replyToken,
					'messages' => $allMessage,
				];
				$post = json_encode($data);
				sendMessage($post);
				
			} else if ($event['type'] == 'follow') {
				createUser($event['source']['userId']);
			}
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
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/changeState.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId . "&userState=" . $userState);
	$result = curl_exec($ch);
	curl_close($ch);
}

//function to check student firstname and surname
function checkStudentName($text) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/checkStudentName.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "text=" . $text);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $result;
}

function checkState($userId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/checkState.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $result[0];
}

function createUser($userId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/createUser.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
}

function setUserStudentId($userId, $studentId){
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/setUserStudentId.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId . "&studentId=" . $studentId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
}

function getUserStudentId($userId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/getUserStudentId.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $result[0];
}

function checkNationalId($userNational, $studentId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/checkNationalId.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userNational=" . $userNational . "&studentId=" . $studentId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return (count($result[0]) != 0) ? $result[0] : '';
}

function getStudent($studentId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/getStudent.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "studentId=" . $studentId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $result[0];
}

function getGrade($studentId){
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$ch = curl_init(BotMessage::$HOST ."api/getGrade.php");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "studentId=" . $studentId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	return $result;
}

function getMainMenu($userId) {
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$studentId = getUserStudentId($userId);
	$studentDetail = getStudent($studentId);
	return $botMessage->REQUEST_MAIN_MENU . " '" . $studentDetail['studentFirstName'] . " " . $studentDetail['studentLastName'] . "'\n" . $botMessage->REQUEST_MAIN_INFO . "\n" . $botMessage->REQUEST_MAIN_PICTURE . "\n" . $botMessage->REQUEST_MAIN_GRADE . "\n" . $botMessage->REQUEST_MAIN_TABLE . "\n" . $botMessage->REQUEST_MAIN_NEW_STUDENT;				
}

function getAfterMain(){
	$botMessage = new BotMessage();
	$botMessage->getMessages();
	$replyText2 = $botMessage->REQUEST_AFTER_MAIN . "\n";
	$replyText2 .= $botMessage->REQUEST_CHOICE_YES . "\n";
	$replyText2 .= $botMessage->REQUEST_CHOICE_NO;
	return $replyText2;
}
?>
