<?php
include_once('../db.php');

$studentId = $_REQUEST['studentId'];
getGrade($studentId);

function getGrade($studentId){
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT * FROM `chatbotGrade` WHERE `studentId` = '" . $studentId . "'");
	$rows = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$rows[] = $temp;
	}
	echo json_encode($rows);
}
?>