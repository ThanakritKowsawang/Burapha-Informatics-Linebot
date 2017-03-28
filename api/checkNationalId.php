<?php
include_once('../db.php');

$userNational = $_REQUEST['userNational'];
$studentId = $_REQUEST['studentId'];
checkNationalId($userNational, $studentId);

function checkNationalId($userNational, $studentId) {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT `studentId` FROM `chatbotStudent` WHERE `studentId` = '" . $studentId . "' AND `userNationId` = '" . $userNational . "'");

	$response = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp['studentId'];
	}
	echo json_encode($response);
}
?>