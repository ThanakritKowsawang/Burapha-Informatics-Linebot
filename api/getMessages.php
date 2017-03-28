<?php
include_once('../db.php');

getMessages();

function getMessages() {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT * FROM `chatbotMessage`");
	$rows = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$rows[] = $temp;
	}
	echo json_encode($rows);
}
?>