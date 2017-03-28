<?php
class BotMessage {
	public static $HOST = 'https://angsila.cs.buu.ac.th/~56160329/Burapha-Informatics-Linebot/';	
	public static $STATUS_DEFAULT = 0;			//default (show request student name) --> 1
	public static $STATUS_WAIT_STUDENT_NAME = 1;	//wait for a student name 
									// (if false show request student name)
									// (if true show request national) --> 2
	public static $STATUS_WAIT_NATIONAL = 2;		//wait for a national id card
									// (if false show request student name) --> 1
									// (if true show main menu) --> 3
	public static $STATUS_WAIT_MAIN_QUEST = 3;	//wait for a main answer
									// (if MAIN_INFO show student's info, show after main) --> 5
									// (if MAIN_PICTURE show student's picture, show after main) --> 5
									// (if MAIN_GRADE show student's current GPA, show term GPA question) --> 4
									// (if MAIN_TABLE show student's class table, show after main) --> 5
									// (if MAIN_NEW_STUDENT show request student name) --> 1
	public static $STATUS_WAIT_TERM_QUEST = 4;	//wait for a term answer
									// (if QUEST_NO show after main) --> 5
									// (if QUEST_YES show term GPA, show after main) --> 5
	public static $STATUS_AFTER_MAIN = 5;			//after select main menu (show yes/no question)
									// (if QUEST_NO show thank you) --> 6
									// (iaf QUEST_YES show main menu) --> 3
	public static $STATUS_SHOW_MAIN = 6;			//when input text or others will show show main menu --> 3

	public $REQUEST_STUDENT_NAME = "คุณต้องการทราบข้อมูลของนิสิตชื่ออะไรครับ?";
	public $REQUEST_FALSE_STUDENT = "ไม่พบชื่อนี้ในระบบ กรุณาลองอีกครั้ง";
	public $REQUEST_NATIONAL_ID = "กรุณาพิมพ์เลขบัตรประชาชนผู้ปกครอง";
	public $REQUEST_FALSE_NATIONAL_ID = "ข้อมูลเลขบัตรของคุณไม่ตรงกับข้อมูลนิสิต กรุณาพิมพ์ชื่อนิสิตอีกครั้ง";
	public $REQUEST_MAIN_MENU = "ต้องการข้อมูลอะไรของ";
	public $REQUEST_MAIN_INFO = "-> ข้อมูลทั่วไป";
	public $REQUEST_MAIN_PICTURE = "-> รูปถ่าย";
	public $REQUEST_MAIN_GRADE = "-> เกรด";
	public $REQUEST_MAIN_TABLE = "-> ตารางเรียน";
	public $REQUEST_MAIN_NEW_STUDENT = "-> เลือกนิสิตใหม่";
	public $REQUEST_FALSE_MAIN_MENU = "ไม่พบรายการที่คุณต้องการ กรุณาพิมพ์ใหม่อีกครั้ง";
	public $REQUEST_INFO_NAME = "ชื่อ: ";
	public $REQUEST_INFO_STUDENT_ID = "รหัสนิสิต: ";
	public $REQUEST_INFO_BIRTHDATE = "วันเกิด: ";
	public $REQUEST_INFO_FACULTY = "คณะ: ";
	public $REQUEST_INFO_BRANCH = "สาขา: ";
	public $REQUEST_INFO_LEVEL = "ชั้นปี: ";
	public $REQUEST_INFO_STATUS = "สถานะ: ";
	public $REQUEST_AFTER_MAIN = "คุณต้องการทราบข้อมูลอื่นๆอีกหรือไม่?";
	public $REQUEST_CHOICE_YES = "-> ใช่";
	public $REQUEST_CHOICE_NO = "-> ไม่";
	public $REQUEST_THANK_YOU = "ขอบคุณที่ใช้บริการครับ ";
	public $REQUEST_GRADE = "เกรดเฉลี่ยสะสมปัจจุบัน: ";
	public $REQUEST_GRADE_TERM = "ปี: " ;
	public $REQUEST_GRADE_SUM = " เกรด ";
	public $REQUEST_GRADE_CHOICE = "ต้องการดูเกรดเฉลี่ยแต่ละปีการศึกษาหรือไม่? ";

	public function getMessages() {
		$ch = curl_init(BotMessage::$HOST ."api/getMessages.php");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch), true);
		curl_close($ch);

		$REQUEST_STUDENT_NAME = $result[0]['msgText'];
		$REQUEST_FALSE_STUDENT = $result[1]['msgText'];
		$REQUEST_NATIONAL_ID = $result[2]['msgText'];
		$REQUEST_FALSE_NATIONAL_ID = $result[3]['msgText'];
		$REQUEST_MAIN_MENU = $result[4]['msgText'];
		$REQUEST_MAIN_INFO = $result[5]['msgText'];
		$REQUEST_MAIN_PICTURE = $result[6]['msgText'];		
		$REQUEST_MAIN_GRADE = $result[7]['msgText'];
	 	$REQUEST_MAIN_TABLE = $result[8]['msgText'];
	 	$REQUEST_MAIN_NEW_STUDENT = $result[9]['msgText'];
	 	$REQUEST_FALSE_MAIN_MENU = $result[10]['msgText'];
	 	$REQUEST_INFO_NAME = $result[11]['msgText'];
	 	$REQUEST_INFO_STUDENT_ID = $result[12]['msgText'];
	 	$REQUEST_INFO_BIRTHDATE = $result[13]['msgText'];
	 	$REQUEST_INFO_FACULTY = $result[14]['msgText'];
	 	$REQUEST_INFO_BRANCH = $result[16]['msgText'];
	 	$REQUEST_INFO_LEVEL = $result[17]['msgText'];
	 	$REQUEST_INFO_STATUS = $result[18]['msgText'];
	 	$REQUEST_AFTER_MAIN = $result[19]['msgText'];
	 	$REQUEST_CHOICE_YES = $result[20]['msgText'];
	 	$REQUEST_CHOICE_NO = $result[21]['msgText'];
	 	$REQUEST_THANK_YOU = $result[22]['msgText'];
	 	$REQUEST_GRADE = $result[23]['msgText'];
	 	$REQUEST_GRADE_TERM = $result[24]['msgText'];
	 	$REQUEST_GRADE_SUM = $result[25]['msgText'];
	 	$REQUEST_GRADE_CHOICE = $result[26]['msgText'];
	}
}
?>