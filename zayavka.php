<?php
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1;"));
if ($user['fiz'] > 0) header("Location: fight.php");

$mytime=time()+1200;
mysql_query("UPDATE users SET `online`=1, `ontime`='".mysql_real_escape_string($mytime)."'  WHERE `id`= '{$user['id']}'");
require_once 'header.html';
echo '<form method="post" action="main.php">
<input type="submit"  value="Вернуться">
</form>';
echo '<form method="post" action="stena.php">
<input type="submit" name="stena" value="Стена сообщений">
</form>';
echo '<form action=zayavka.php><input type="submit"   value ="обновить" /></form>';
echo '<br/>Напасть на:';
$all_users=mysql_query("SELECT `login` FROM `users` WHERE `online`=1 AND `ontime` > '".time()."' AND `npc`=0  LIMIT  50;");
$all_u=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) FROM `users` WHERE `online`=1 AND `ontime` > '".time()."' AND `npc`=0  LIMIT  50;"));
print "| Всего игроков " . $all_u['COUNT(*)'];
if (file_get_contents("1tmp.txt" ) < $all_u['COUNT(*)']) {
echo " Новый игрок!";
print "<audio  src=zvuk.mp3  autoplay> </audio>";
}
file_put_contents("1tmp.txt", $all_u['COUNT(*)'] );

	while($val = mysql_fetch_array($all_users)) {

	echo '<form method="post">
	<input type="submit" name="zayavka" value="'.$val['login'].'">
	<a href="info.php?login='.$val['login'].'" target=_blank> (анкета) </a>
	</form>';

	}

	if (isset($_POST['zayavka']) && $_POST['zayavka'] !==$user['login']) {
	$enemy=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `login`='".mysql_real_escape_string($_POST['zayavka'])."'  LIMIT 1;"));

	if( $enemy['hp'] < 5) {
	echo ' Противник слишком ослаблен! ';
	}
	elseif( $user['hp'] < 5) {
	echo ' Не хватает энергии для боя! ';
	}
	elseif( $enemy['fiz'] !=0 ) {
	echo ' Противник уже в бою!  ';
	}
	elseif ($enemy['battle']==0 && $enemy['online']==1) {
	mysql_query("INSERT INTO `battle` (`id`, `t1`, `t2`, `t1_h`, `t2_h`, `t1_b`, `t2_b`) values (NULL, '{$user['id']}', '{$enemy['id']}' , '".mt_rand(1,2)."', '".mt_rand(1,2)."', '".mt_rand(1,2)."', '".mt_rand(1,2)."');");
	$bat=mysql_fetch_assoc(mysql_query("SELECT * FROM `battle` WHERE `t1`='{$user['id']}' AND  `t2`='{$enemy['id']}' LIMIT 1;"));

	mysql_query("UPDATE users SET `battle`=1, `fiz`=1, `battle_n`='{$bat['id']}', `enemy_id`='{$user['id']}'  WHERE `id`= '{$enemy['id']}'");

	mysql_query("UPDATE users SET `battle`=1, `fiz`=1, `battle_n`='{$bat['id']}', `enemy_id`='{$enemy['id']}'  WHERE `id`= '{$user['id']}'");

	echo ' Бой между ' .$user['login']. ' и ' .$enemy['login']. ' начался! ';
	echo "<meta http-equiv=\"refresh\" content=\"2; URL=zayavka.php\">";
	}
	elseif($_POST['zayavka'] && $user['fiz']==1) {
	echo 'Бой в разагаре!';
	}
	else {
	echo ' Персонаж не в игре или в бою! Или слишком ослаблен!';
	}
	}
	else {
	echo ' Для поединка игроки должны быть онлайн...';
	}
	echo "<meta http-equiv=\"refresh\" content=\"5; URL=zayavka.php\">";
	require_once 'footer.html';
	?>
