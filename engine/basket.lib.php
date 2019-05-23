<?php

 
function AddToOrder($name,$phone,$adres) {
	$id_session=session_id();
	$name=strip_tags(mysqli_real_escape_string(getConnection(),$name));
	$phone=strip_tags(mysqli_real_escape_string(getConnection(),$phone));
	$adres=strip_tags(mysqli_real_escape_string(getConnection(),$adres));
	executeQuery("INSERT INTO `orders` (`session`, `status`, `name`, `phone`, `adres`)
 VALUES ('{$id_session}', '1', '{$name}', '{$phone}', '{$adres}');");
}

function getCountGoodsInBasket() {
	$id_session=session_id();
	$result=getRowResult("SELECT sum(col) as sum FROM `basket` WHERE `session`='{$id_session}'");
return $result["sum"];
}

function getMyBasket() {
	$id_session=session_id();
	$sql = "SELECT @i:=@i+1 as number, `goods`.*,`basket`.* FROM `goods`,`basket`,(SELECT @i:=0) as X 
WHERE `goods`.`idx`=`basket`.`id_good` AND `basket`.`session`='{$id_session}'";
	$goods = getAssocResult($sql);
	
return $goods;
}

function getSummGoodsInBasket() {
	$id_session=session_id();
	$result=getRowResult("SELECT sum(price*col) as sum FROM `goods`,`basket` 
WHERE `goods`.`idx`=`basket`.`id_good` AND `session`='{$id_session}'");
return $result["sum"];
}

function DeleteFromBasket($id_good) {
	$id_session=session_id();
	executeQuery("DELETE FROM `basket` 
		WHERE `basket`.`idx` = {$id_good} AND `basket`.`session` = '{$id_session}'");
}
 
function AddToBasket($id_good) {
	$id_session=session_id();

	//Проверим есть ли уже такой товар в корзине
	$result=getRowResult("SELECT col FROM `basket` 
	WHERE `session`='{$id_session}' AND `id_good`={$id_good}");
	$col=$result["col"];

	//если есть увеличим счетчик товара
	if ($col!=null) 
		executeQuery("UPDATE `basket` SET `col`=`col`+1 
		WHERE `session`='{$id_session}' AND `id_good`={$id_good};");
	else	
		//если нет то просто добавим товар в корзину с количеством 1
		executeQuery("INSERT INTO `basket` (`session`, `id_good`, `col`) 
		VALUES ('{$id_session}', '{$id_good}', 1);");

}
 
 
 
