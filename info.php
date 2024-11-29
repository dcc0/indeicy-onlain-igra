<?php
include 'checksession.php';
require 'config.php';
$inf=mysql_fetch_assoc(mysql_query("SELECT `id`, `lvl`, `exp`, `win`, `lose`, `draw`, `online`, `ontime` FROM `users`  WHERE `login`='".mysql_real_escape_string($_GET['login'])."'  LIMIT 1;")) or exit('<strong>HET...</strong>');
$anketa=mysql_fetch_assoc(mysql_query("SELECT  * FROM `anketa`  WHERE `for_id`='".((int)$inf['id'])."'  LIMIT 1;"));
require_once 'header.html';
print "<center>__⚔__<br/>
♞ ♝ ♜</center><br/>";
echo ' Анкета игрока <strong><br/>'. $_GET['login'] . ' (' .$inf['lvl']. ') </strong><br/>';
if($inf['online'] > 0 && time() < $inf['ontime']) {
$me='<font color=green> on </font>';
} else {
$me=' off ';
}

if($anketa['avatar']!='') {
echo '<img src="'.$anketa['avatar'].'" width="100" height="100">'.$me.'<br/>';
}
else {
echo $me . '<br/>';
}
echo 'Опыт:' . $inf['exp'] . '<br/>';
echo 'Побед:' . $inf['win'] . '<br/>';
echo 'Поражений:' . $inf['lose'] . '<br/>';
echo 'Ничьих:' . $inf['draw'] . '<br/>';
echo nl2br($anketa['anketa']);

if ($_SESSION['uid'] > 0) {
$user=mysql_fetch_assoc(mysql_query("SELECT `login`, `lvl` FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
$you_mail=mysql_fetch_assoc(mysql_query("SELECT `mail` FROM `anketa`  WHERE `for_id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
echo '</br></br><tt>Отправить телеграмму <tt></br>
<form  method="POST">
<input type="hidden" name="you_mail" value="'.$you_mail['mail'].'">
<input type="hidden" name="senderr" value="'.$user['login'].'">
<input type="text" name="themess" size="40%" maxlength="1000">
<br/>
<button name="sub_mail"  />Отправить</button>
</form>';

if(isset($_POST['sub_mail']) && $_POST['themess']!='' && strlen($_POST['themess']) <1001) {

		$decode=mb_detect_encoding($_POST['themess']);
		if($decode !='UTF-8') {
       		$_POST['themess'] = iconv($decode, "UTF-8", $_POST['themess'] );
       		}

if($user['lvl'] < 1) {
echo ' Доступно с 1-го уровня! ';
}
elseif (!preg_match("|^([a-z0-9_.-]{1,20})@([a-z0-9.-]{1,20}).([a-z]{2,4})|is", strtolower($you_mail['mail']))) {
echo ' Требуется указать email в анкете ';
}
elseif (!preg_match("|^([a-z0-9_.-]{1,20})@([a-z0-9.-]{1,20}).([a-z]{2,4})|is", strtolower($anketa['mail']))) {
echo ' У получателя нет адреса в анкете! ';
}
elseif(!preg_match('/^[a-zа-яё0-9\.\,\!\?\;\#\&\-\+\=:\s]+$/ui', $_POST['themess'])) {
	echo ' Запрещённый символ! ';
}
else {
$_POST['themess'] = substr($_POST['themess'], 0, 1000);
$_POST['themess']=strip_tags($_POST['themess']);

$to      = $anketa['mail'];
$subject = 'Mail fom IndiansOnline Game';
$message = 'From player: '.$user['login'].', Адрес для ответа (нажмите): '.$you_mail['mail']."\r\n".$_POST['themess'];
  $headers = 'Reply-To: ' .$you_mail['mail']. "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
echo " Телеграмма в пути... ";
echo "<meta http-equiv=\"refresh\" content=\"3\">";
}
}
}
?>
<?php
require_once 'footer.html';
?>
