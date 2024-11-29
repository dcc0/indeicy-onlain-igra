<?php 
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

if(isset($_POST['sub'])) {
$check_ip=mysql_fetch_assoc(mysql_query("SELECT * FROM `br_check` WHERE `ip`='".mysql_real_escape_string($ip)."' LIMIT 1;"));
if($_POST['login']=='' ) {
echo '<strong>ENTER PASSWORD!</strong>';
}
elseif($_POST['phrase']=='') {
echo '<strong>ENTER PASSWORD!</strong>';
} 
else {
$ch_user=mysql_fetch_assoc(mysql_query("SELECT `id`, `login`, `phrase` FROM `users` WHERE `login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1;"));
if(strcasecmp($ch_user['login'], $_POST['login'])!=0) {
echo '<strong>INCORRECT LOGIN OR PASSWORD!</strong>';
}
elseif($ip === $check_ip['ip'] ) {
echo '<strong>LAST ATTEMPT FAILED! WAIT FOR 30 SEC!</strong>';
}	
elseif($ch_user['phrase']!==md5($_POST['phrase'])) {
echo '<strong>INCORRECT LOGIN OR PASSWORD!</strong>';

$timer=time()+30;
mysql_query("INSERT INTO `br_check` (`id`, `ip`, `timer`) values (NULL, '".mysql_real_escape_string($ip)."', '".((int)$timer)."');") or exit ('Ошибка записи!');
}
else {
session_start();
$_SESSION['uid'] = $ch_user['id'];
header("Location: main.php");
}
}
}
$check_ip=mysql_fetch_assoc(mysql_query("SELECT * FROM `br_check` WHERE `ip`='".mysql_real_escape_string($ip)."' LIMIT 1;"));
if($ip===$check_ip['ip'] && time() > $check_ip['timer'] && $check_ip['timer']!='') {
mysql_query("DELETE FROM `br_check` WHERE  `ip`='".mysql_real_escape_string($ip)."' LIMIT 1");
}
?>