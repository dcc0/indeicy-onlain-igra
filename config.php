<?php
//Crafted by MoLoТ (mol0t@list.ru) aka dcc0 aka iv777 aka BlackRus (Ivan Gavryushin 2017-2024)
//Для реализации этого движка я черпал вдохновение в движке БК1(2002). Но движок написан с чистого листа.
function connect() {
	 define('HOST', 'localhost');
     define('USER', 'root');
     define('PASSWORD', '123');
     
$connect=mysql_connect(HOST, USER, PASSWORD) or exit('Нет соединения!');
mysql_query("SET NAMES utf8");
return $connect;
}

function select_bd($connect) {
   	 define('NAME_BD', 'game');
return mysql_select_db(NAME_BD,$connect) or exit ('Нет такой базы!');
}

$connect=connect();
select_bd($connect);

?>
