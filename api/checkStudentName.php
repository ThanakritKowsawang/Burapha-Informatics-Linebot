<?php
include_once('../db.php');

$text = $_REQUEST['text'];
checkStudentName($text);

function checkStudentName($text) {

	$objConn = new db();
	$objConn->connect();
	$text = trim($text, ' ');
	$doubleName = explode(' ', $text);
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
	echo json_encode($resultArray);
}
?>