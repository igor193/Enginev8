<?php
/**
 * готовим страницу каталога
 */
function prepareCataloguePage(){
 $sql = "SELECT * FROM `goods` WHERE 1;";
 $goods = getAssocResult($sql);

   return $goods;
}


