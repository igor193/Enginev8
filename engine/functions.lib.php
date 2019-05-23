<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/**
 * Подготовка переменных для разных страниц
 * @param $page_name
 * @return array
 */
function prepareVariables($page_name, $action = ""){
    $vars["title"] = SITE_TITLE;
	if (AlreadyLogin()) {
		$clear_vars["user"]=$_SESSION['login'];
		$clear_vars["back_url"]=$_SERVER["REQUEST_URI"];
		
		$vars["login"] = renderPage("logout_block",$clear_vars);
	}
	else
		$vars["login"] = renderPage("login_block",["back_url"=>$_SERVER["REQUEST_URI"]]);
	
    switch ($page_name){
        case "index":
            $vars["greetings"] = "Добро пожаловать в наш интернет-магазин ноутбуков, у нас огромный
						ассортимент комплектующих и расходных материалов для ремонта ноутбуков, планшетов 
						и телефонов. ";
		break; 
	
        case "login":
			$back=strip_tags($_GET["back"]);
			$login=$_POST["login"];
			$pass=$_POST["pass"];
			Auth($login,$pass);
			header("Location: {$back}");
        break; 	
			
        case "logout":
			$back=strip_tags($_GET["back"]);
			unset($_SESSION["login"]);
			unset($_SESSION["pass"]);
			header("Location: {$back}");
        break; 	
		
        case "basket":
			if ($action=="delete") {
				$id_good=(int)$_GET["idx"];
				DeleteFromBasket($id_good);
				header("Location: /basket/");
			}
			if ($action=="buy") {
				$id_good=(int)$_POST["id_good"];
				AddToBasket($id_good);
				header("Location: /catalogue/");				
			}
			$vars["amount"]=getSummGoodsInBasket();
            $vars["basket_content"]=getMyBasket();
        break;     
			
		case "news":
            $vars["newsfeed"] = getNews();
            break;
			
        case "newspage":
            $content = getNewsContent($_GET['id_news']);
            $vars["news_title"] = $content["news_title"];
            $vars["news_body"] = $content["news_body"];
            break;

			
        case "catalogue":
			$vars["goods"]=prepareCataloguePage();
            break;
			
		case "guestbook":
			if ($action=="add") {
				$name=$_POST["name"];
				$email=$_POST["email"];
				$text=$_POST["text"];
				AddRecordToGuestbook($name,$email,$text);
				header("Location: /guestbook/");
			}
			$vars["guestbookfeed"]=getAllGuestbook();
            break;
		
		
		case "order":
			$id_good=(int)$_POST["id_good"];
			$name=$_POST["name"];
			$phone=$_POST["phone"];
			$adres=$_POST["adres"];
			AddToOrder($name,$phone,$adres);
			session_regenerate_id();	
			header("Location: /orderconfirm/");
            break;
					
		
		case "admin":
			if ($action=="confirmed") {
				$idx=(int)$_GET["idx"];
				ConformOrder($idx);
				header("Location: /admin/");
			}
			$vars["orders"]=prepareOrders();
			
            break;
			
    }

    $clear_vars = $vars;
    $clear_vars["main_menu_links"] = getMainMenuLinks();
    $clear_vars["main_menu"] = renderPage("main_menu_block", $clear_vars);
	
    $vars["header"] = renderPage("header_block", $clear_vars);
	$vars["footer"] = renderPage("footer_block");

    return $vars;
}

//формируем динамическое меню
function getMainMenuLinks(){
	//определим количество товаров в корзине
	$ngoods=getCountGoodsInBasket();
	$menu= [
        [
          "link" => "/",
          "link_title" => "Главная"
        ],
        [
            "link" => "/catalogue/",
            "link_title" => "Каталог"
        ],      
		[
            "link" => "/news/",
            "link_title" => "Новости"
        ],
        [
            "link" => "/guestbook/",
            "link_title" => "Отзывы"
        ],     
		[
            "link" => "/contacts/",
            "link_title" => "Контакты"
        ]
			];
	//покажем пункт с корзиной если она не пуста
	if ($ngoods!=0)
		$menu[]=
		[
            "link" => "/basket/",
            "link_title" => "Корзина (" . $ngoods . ")"
        ];	
	if (AlreadyLogin($login,$pass))
		$menu[]=
		[
            "link" => "/admin/",
            "link_title" => "Админка"
        ];
	return $menu;
    
}
