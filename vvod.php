
<?php
require_once 'header.html';
$page = $_SERVER['PHP_SELF'];

include 'checksession.php';
if (!($_SESSION['uid'] > 0)) { exit("<a href=\"$page\">Зайти в игру, потом нажать сюда...</a>");
}
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1;"));
//if ($user['fiz'] == 0 && $user['battle'] > 0) header("Location: main.php");
//if ($user['fiz'] == 1 ) header("Location: fight.php");
require_once 'header.html';

?>

<form  method="post"><br/>
Сообщение <br/><input type="text" name="text" size="50%" maxlength="100"><br/>
<input type="submit" name="sub" value="Отправить">
<input type="button" onclick="window.location.reload()"  value ="Обновить" />
</form>
<link href="style.css" type="text/css" rel="stylesheet">

<?
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));


?>




<?php
if(isset($_POST['sub']) && $_POST['text'] !='' ) {

		$_POST['text'] = substr($_POST['text'], 0, 100);

if(!preg_match('/^[a-zа-яё0-9\.\,\!\?\;\#\&\)\(\-\/\@\+\=:\s]+$/ui', $_POST['text'])) {
	echo ' Запрещенный символ! ';
} else {
$_POST['text']=strip_tags($_POST['text']);
mysql_query("INSERT INTO `chat` (`id`, `login`, `text`) values (NULL, '".mysql_real_escape_string($user['login'])."', '".mysql_real_escape_string($_POST['text'])."');");
$query = mysql_query("SELECT COUNT(*) FROM chat");
$row = mysql_fetch_array($query);
if ($row['0'] > 30 ){
mysql_query("DELETE FROM `chat`	 ORDER BY id ASC LIMIT 1  ") or exit ('not ok');
}
echo "<meta http-equiv=\"refresh\" content=\"1; URL=vvod.php\">";
}
}



require_once 'footer.html';
?>
