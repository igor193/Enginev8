<?php
/**
 * готовим страницу каталога
 */
 
function ConformOrder($idx) {
	$sql="UPDATE `orders` SET `status` = '2' WHERE `orders`.`idx` = {$idx}";
	executeQuery($sql);
}
 
//грязный трюк (автор)
function prepareOrders(){
$out="";
$connect=mysqli_connect(HOST, USER, PASS, DB);
$result=mysqli_query($connect,"SELECT * FROM `orders` WHERE 1 ORDER BY status;");
		$n=1;
		while ($row=mysqli_fetch_assoc($result)) {
			$out.= "<center>";
					$status="Новый";
					if ($row['status']==2) $status="Обработан";
					$out.= "{$n}: Имя:{$row['name']} Телефон: {$row['phone']}
					Адрес: {$row['adres']} Статус:	{$status} 
					<a href='confirmed/?idx={$row['idx']}'><button>Обработать</button></a><br>";
					$n++;
				
	
		$out.="</center>
        <table border='1' cellpadding='5' cellspacing='0'>
			<tr>
				<td>№</td><td>Наименование</td><td>Количество</td><td>Цена</td>
			</tr>";
		
				$result2=mysqli_query($connect,"SELECT * FROM `goods`,`basket` 
				WHERE `goods`.`idx`=`basket`.`id_good` AND `basket`.`session`='{$row['session']}';");
				//Посчитаем сумму цен товаров
				$result3=mysqli_query($connect,"SELECT sum(price*col) as sum FROM `goods`,`basket` 
				WHERE `goods`.`idx`=`basket`.`id_good` AND `session`='{$row['session']}';");
				$row3=mysqli_fetch_assoc($result3);
				$summ=$row3["sum"];
				$i=1;
				while ($row2=mysqli_fetch_assoc($result2)) {
					$out.= "<tr>
							<td>{$i}</td>
							<td>{$row2['name']}</td>
							<td>{$row2['col']}</td>
							<td>{$row2['price']}</td>
						</tr>";
					$i++;
				}
				$out.= "<tr>
				<td colspan='5'>Итого товаров на сумму: {$summ} рублей.</td>
			</tr>";
			$out.= "</table><br><br>";
			}
			

 
 return $out;



}
//как надо, трюкач фигов (совесть)
function prepareOrders2(){
 $sql = "SELECT * FROM `orders` WHERE 1;";
 $orders = getAssocResult($sql);
 $neworders=[];
 foreach ($orders as $i=>$value) {
	 $sql="SELECT * FROM `goods`,`basket` 
				WHERE `goods`.`idx`=`basket`.`id_good` AND `basket`.`session`='{$value["session"]}'";
				
			$suborders = getAssocResult($sql);	
				
	 $value["subtable"]=$suborders;
	  $neworders[]=$value;

 }

 
 return $neworders;



}



