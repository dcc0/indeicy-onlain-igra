<?php 
require_once 'header.html';
require 'config.php';
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		else
			{
			$ip=$_SERVER['REMOTE_ADDR'];
			}

$check_ip=mysql_fetch_array(mysql_query("SELECT * FROM `prereg` WHERE `ip`='".mysql_real_escape_string($ip)."' LIMIT 1;"));

if( empty($check_ip['id'])) { 

if(isset($_POST['sub'])) {

if($_POST['login']=='' ) {
echo 'Не указан логин!';
}
elseif(strlen($_POST['login']) < 3 ) {
echo 'Логин должен быть длиной не менее 3 символов!';
}
elseif($_POST['phrase']=='') {
echo 'Не указан пароль!';
}
elseif(strlen($_POST['phrase']) < 5) {
echo 'Пароль должен быть длиной не менее 5 символов!';
}
elseif(strlen($_POST['phrase']) > 30) {
echo 'Пароль должен быть длиной не более 30 символов!';
}
elseif(!preg_match('/^[a-zа-яё0-9\_\-\s]+$/ui', $_POST['login'])) {	
echo 'Запрещенный символ!';
}
elseif(strlen($_POST['login'])> 30) {	
echo 'Не больше 30 симолов!';
}
elseif(is_numeric($_POST['login'])) {	
echo 'Нельзя только цифры!';
}
else {
$ch_user=mysql_fetch_assoc(mysql_query("SELECT `login` FROM `users` WHERE `login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1;"));
if(strcasecmp($ch_user['login'], $_POST['login'])==0) {
echo 'Такой пользователь уже есть!';
}
else {
$timer=time()+2400;
	   mysql_query("INSERT INTO `users` (`id`, `login`,`phrase`,`date`,`city`, `lvl`, `hpx`, `hp`) values (NULL, '".mysql_real_escape_string($_POST['login'])."', '".mysql_real_escape_string(md5($_POST['phrase']))."', '".mysql_real_escape_string(time())."', 'Столица', '0', '10', '10' );") or exit ('Ошибка записи!');
   
 mysql_query("INSERT INTO `prereg` (`id`, `ip`, `timer`) values (NULL, '".mysql_real_escape_string($ip)."', '".((int)$timer)."');") or exit ('Ошибка записи!');

$ch_user2=mysql_fetch_assoc(mysql_query("SELECT `id` FROM `users` WHERE `login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1;"));
echo $ch_user2['login'];
if($ch_user2['id'] > 0 ) {
echo ' Успешно создан! ';
echo "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
}
else {
echo ' Что-то пошло не так! ';
}
}
}
}
}
else {
echo 'Требуется подождать 40 минут!';

}
$check_ip=mysql_fetch_assoc(mysql_query("SELECT * FROM `prereg` WHERE `ip`='".mysql_real_escape_string($ip)."' LIMIT 1;"));
if($check_ip['id'] > 0  && time() > $check_ip['timer'] ) {
mysql_query("DELETE FROM `prereg` WHERE  `ip`='".mysql_real_escape_string($ip)."' LIMIT 1");
}
?>
<br/>Регистрация <br/>
<form name="test" method="post"><br/>
Имя <br/><input type="text" name="login" size="30" maxlength="40"><br/>
Пароль <br/><input type="text" name="phrase" size="30"><br/>
<input type="submit" name="sub" value="Отправить">
</form>

<?php
require_once 'footer.html';
?>