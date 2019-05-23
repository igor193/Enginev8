<?php

function getAllGuestbook() {

	$sql = "SELECT * FROM `guestbook` WHERE 1;";
	$book = getAssocResult($sql);
	
return $book;
}

function AddRecordToGuestbook($name,$email,$text) {
$name=strip_tags(mysqli_real_escape_string(getConnection(),$name));
$email=strip_tags(mysqli_real_escape_string(getConnection(),$email));
$text=strip_tags(mysqli_real_escape_string(getConnection(),$text));
$data=date("Y-m-d");

executeQuery("INSERT INTO `guestbook` (`name`, `email`, `text`, `data`)
 VALUES ('{$name}', '{$email}', '{$text}', '{$data}');");
}

