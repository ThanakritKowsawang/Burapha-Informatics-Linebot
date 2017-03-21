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

	public static $MAIN_INFO = 1;					//info of student
	public static $MAIN_PICTURE = 2;				//pickture of student
	public static $MAIN_GRADE = 3;				//grade of student
	public static $MAIN_TABLE = 4;				//class table of student
	public static $MAIN_NEW_STUDENT = 5;			//choose new student

	public static $QUEST_YES = 1;					//say yes
	public static $QUEST_NO = 0;					//say no

	public static $REQUEST_STUDENT_NAME = "คุณต้องการทราบข้อมูลของนิสิตชื่ออะไรครับ?";
	public static $REQUEST_FALSE_STUDENT = "ไม่พบชื่อนี้ในระบบ กรุณาลองอีกครั้ง";
	public static $REQUEST_NATIONAL_ID = "กรุณาพิมพ์เลขบัตรประชาชนผู้ปกครอง";
	public static $REQUEST_FALSE_NATIONAL_ID = "ข้อมูลเลขบัตรของคุณไม่ตรงกับข้อมูลนิสิต กรุณาพิมพ์ชื่อนิสิตอีกครั้ง";
	public static $REQUEST_MAIN_MENU = "ต้องการข้อมูลอะไรของ";
	public static $REQUEST_MAIN_INFO = "-> ข้อมูลทั่วไป";
	public static $REQUEST_MAIN_PICTURE = "-> รูปถ่าย";
	public static $REQUEST_MAIN_GRADE = "-> เกรด";
	public static $REQUEST_MAIN_TABLE = "-> ตารางเรียน";
	public static $REQUEST_MAIN_NEW_STUDENT = "-> เลือกนิสิตใหม่";
	public static $REQUEST_FALSE_MAIN_MENU = "ไม่พบรายการที่คุณต้องการ กรุณาพิมพ์ใหม่อีกครั้ง";
	public static $REQUEST_INFO_NAME = "ชื่อ: ";
	public static $REQUEST_INFO_STUDENT_ID = "รหัสนิสิต: ";
	public static $REQUEST_INFO_BIRTHDATE = "วันเกิด: ";
	public static $REQUEST_INFO_FACULTY = "คณะ: ";
	public static $REQUEST_INFO_BRANCH = "สาขา: ";
	public static $REQUEST_INFO_LEVEL = "ชั้นปี: ";
	public static $REQUEST_INFO_STATUS = "สถานะ: ";
	public static $REQUEST_AFTER_MAIN = "คุณต้องการทราบข้อมูลอื่นๆอีกหรือไม่?";
	public static $REQUEST_CHOICE_YES = "-> ใช่";
	public static $REQUEST_CHOICE_NO = "-> ไม่";
	public static $REQUEST_THANK_YOU = "ขอบคุณที่ใช้บริการครับ ";
	public static $REQUEST_GRADE = "เกรดเฉลี่ยสะสมปัจจุบัน: ";
	public static $REQUEST_GRADE_TERM = "ปี: " ;
	public static $REQUEST_GRADE_SUM = " เกรด ";
	public static $REQUEST_GRADE_CHOICE = "ต้องการดูเกรดเฉลี่ยแต่ละปีการศึกษาหรือไม่? ";
}
?>