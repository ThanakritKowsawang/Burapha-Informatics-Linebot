<?php
include_once('../db.php');

$studentId = $_REQUEST['studentId'];
getStudent($studentId);

function getStudent($studentId) {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentId` = '" . $studentId . "'");
	$response = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp;
	}
	echo json_encode($response);
}
?>