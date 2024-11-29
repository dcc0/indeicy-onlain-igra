<?php
//Crafted by MoLoТ (mol0t@list.ru) aka dcc0 aka iv777 aka BlackRus (Ivan Gavryushin 2017-2024)
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1;"));
if ($user['fiz'] == 1) header("Location: fight.php");
require_once 'header.html';

if (isset($_POST['hint'])) {
echo '<tt>Время восстановления энергии после боя: с чучелом 60 сек.,  с игроком 40 сек.<br/>
Время нахождения игрока онлайн 20 минут. Если Вы онлайн, рекомендуетcя перейти
на страницу со списком игроков - кнопка "Бой с игроком". Иначе рискуете не узнать
о начале боя.</tt>';
}

if($user['ontime']=='' ) {
$mytime=time()+1200;
mysql_query("UPDATE users SET `ontime`='".((int)$mytime)."'  WHERE `id`='".((int)$_SESSION['uid'])."'");
}
if(time() > $user['ontime']) {
mysql_query("UPDATE users SET `online`=0  WHERE `id`='".((int)$_SESSION['uid'])."'");
}
if(isset($_POST['online']) && $user['online']==0) {
	$mytime=time()+1200;
	mysql_query("UPDATE users SET `online`=1, `ontime`='".$mytime."'  WHERE `id`='".((int)$_SESSION['uid'])."'");
 echo "<meta http-equiv=\"refresh\" content=\"1; URL=main.php\">";
}
elseif (isset($_POST['online']) && $user['online']==1) {
	mysql_query("UPDATE users SET `online`=0  WHERE `id`='".((int)$_SESSION['uid'])."'");
	 echo "<meta http-equiv=\"refresh\" content=\"1; URL=main.php\">";
}

if($user['online']==1 && time() < $user['ontime']) {
$me='<font color=green>on</font>';
} else {
$me='off';
}
echo '<form method="post" action="zayavka.php">
<input type="submit"  value="Бой с  игроком">
</form>';
echo '<form method="post" action="#here">
<input type="submit" name="boi" value="Бой с чучелом">
</form>';
echo '<form method="post" action="stena2.php">
<input type="submit" name="stena" value="&#1042;&#1077;&#1083;&#1080;&#1082;&#1080;&#1081; &#1082;&#1086;&#1089;&#1090;&#1105;&#1088; (&#1088;&#1072;&#1079;&#1075;&#1086;&#1074;&#1086;&#1088;&#1099;)">
</form>';
echo '<form method="post" action="main.php">
<input type="submit"  name="online" value="Я онлайн">' .$me. '
</form>';
echo '<form method="post" action="anketa.php">
<input type="submit"  name="anketa" value="Анкета">
</form>';
echo '<form method="post" action="world.php">
<input type="submit"  name="place" value="Местность">
</form>';

$eff=mysql_fetch_assoc(mysql_query("SELECT * FROM `eff`  WHERE `for_id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
if($eff['id']!='' && time() > $eff['timer']) {
mysql_query("UPDATE users SET `hp`='".((int)$user['hpx'])."'  WHERE `id`= '".((int)$user['id'])."'");
mysql_query("DELETE FROM `eff` WHERE `for_id`='".((int)$user['id'])."' LIMIT 1");
}
if($eff['id']=='' && $user['battle'] ==0) {
mysql_query("UPDATE users SET `hp`='".((int)$user['hpx'])."'  WHERE `id`='".((int)$user['id'])."'");
}
if($user['hp'] < 0) {
mysql_query("UPDATE users SET `hp`=0  WHERE `id`='".((int)$user['id'])."'");
}

?>
<br/><img src="./i/obr.png">
<?php
echo $user['login'];
echo '('.$user['lvl'].')';
echo '<br/> Энергия ' .$user['hp'].'/'.$user['hpx'];
echo '<br/><tt> Победы ' .$user['win'];
echo ' Поражения ' .$user['lose'];
echo ' Ничьих ' .$user['draw'];
echo ' Опыт ' .$user['exp'] . '</tt>';


//Автовыбор удара
if(isset($_POST['boi'] ) && $user['hp'] > 3  || ($user['battle'] > 0)) {

	$ck_udar=rand(1, 2);

if ($ck_udar==1) {
$a1="checked";
} elseif ($ck_udar==2) {
$a2="checked";
}

$ck2_udar=rand(1, 2);


if ($ck2_udar==1) {
$a3="checked";
}
elseif ($ck2_udar==2) {
$a4="checked";
}


mysql_query("UPDATE users SET `battle`=1  WHERE `id`='".((int)$_SESSION['uid'])."'");
$bot=mysql_fetch_assoc(mysql_query("SELECT * FROM `bots`  WHERE `for_id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
if($bot['id'] == '') {
mysql_query("INSERT INTO `bots` (`id`, `for_id`, `hp`, `hpx`) values (NULL, '".((int)$user['id'])."', '".((int)$user['hp'])."',  '".((int)$user['hpx'])."');");
echo "<br/> В бой... <meta http-equiv=\"refresh\" content=\"1; URL=main.php\">";
}
elseif ($bot['for_id'] == $user['id']) {
	echo '<p><a name="here"></a></p><br/>';
echo "<form method=\"post\" action=\"#here\">
Удар <br/><tt>Верх часть: </tt><INPUT TYPE=radio  NAME=attack value=1 $a1>
<br/><tt>Нижн. часть:</tt><INPUT TYPE=radio  NAME=attack value=2 $a2><br/>
Блок<br/>";
//$ran=rand(0,2);
  //   if ($ran==1 || $ran==2) {
    //   echo '<img src="i/Untitled.gif " width="27%" height="27%"><br/>';
//}

echo "<tt>Верх часть:</tt> </tt><INPUT TYPE=radio  NAME=block value=1 $a3>
<br/><tt>Нижн. часть:</tt><INPUT TYPE=radio  NAME=block value=2 $a4><br/>u
<input type=\"submit\" name=\"hit\" value=\"Вперед\">
</form>";
echo '<br/><img src="./i/chuch.png">';
echo ' Чучело';
echo '('.$user['lvl'].')';
echo ' <br/>Энергия ' .$bot['hp'].'/'.$bot['hpx']. '<br/>';

}
} elseif(isset($_POST['boi']) && $user['hp'] <= 3 ) {
echo '<br/>Нужно восстановить энергию!';
unset($_POST['boi']);
}
//Функции с крит. ударом, увертыванием
require_once 'functions.php';

if(isset($_POST['hit']) && isset($_POST['block'])) {
$_POST['attack']=(int)$_POST['attack'];
$_POST['block']=(int)$_POST['block'];

$block_rand=mt_rand(1,2);
if ($_POST['attack']==1 && $block_rand==1) {
	echo ' Чечело ставит блок! ';

}
elseif ($_POST['attack']==1 && $block_rand==2) {
	echo ' Попадание! ';
	mysql_query("UPDATE `bots` SET `hp` =`hp`-1  WHERE `for_id`='".((int)$user['id'])."'");
	krit_udar($user_id);

}
elseif ($_POST['attack']==2 && $block_rand==2) {
	echo ' Чечело ставит блок! ';

}

elseif ($_POST['attack']==2 && $block_rand==1) {
	echo ' Попадание! ';
	mysql_query("UPDATE `bots` SET `hp` =`hp`-1  WHERE `for_id`='".((int)$user['id'])."'");
	//Крит
	krit_udar($user_id);
}


 $udar_rand=mt_rand(1,2);
 if ($_POST['block']==1 && $udar_rand==1) {
	echo ' Вы блокируете! ';

}
elseif ($_POST['block']==1 && $udar_rand==2) {
	echo ' Попадание по Вам! ';
	mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`='".((int)$_SESSION['uid'])."'");
	krit_udar_bit($user_id);
}
elseif ($_POST['block']==2 && $udar_rand==2) {
	echo ' Вы блокируете! ';

}

elseif ($_POST['block']==2 && $udar_rand==1) {
	echo ' Попадание по Вам! ';
	mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`='".((int)$_SESSION['uid'])."'");
	krit_udar_bit($user_id);
}
 $magictime=time()+60;
if($user['hp'] <= 0 && $bot['hp'] <= 0  && $bot['id'] != '') {
echo 'Бой закончен! Ничья!';
unset($_POST['boi']);

mysql_query("UPDATE users SET `battle`=0, `draw`=`draw`+1  WHERE `id`= '".mysql_real_escape_string($user['id'])."'");
mysql_query("DELETE FROM `bots` WHERE  `for_id`='".((int)$user['id'])."' LIMIT 1");
mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('".((int)$user['id'])."', '".((int)$magictime)."');");
echo "<meta http-equiv=\"refresh\" content=\"2; URL=main.php\">";
	}
elseif($user['hp'] > 0 && $bot['hp'] < 1 && $bot['id'] != '') {
echo 'Вы победили!';
unset($_POST['boi']);
mysql_query("UPDATE users SET `battle`=0,  `win`=`win`+1, `exp`=`exp`+1 WHERE `id`='".((int)$user['id'])."'");
mysql_query("DELETE FROM `bots` WHERE  `for_id`='".((int)$user['id'])."' LIMIT 1");
mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('".((int)$user['id'])."', '".((int)$magictime)."');");

if($user['exp'] > 0 && preg_match("/^[0-9]{1,}0$/", $user['exp'])) {
mysql_query("UPDATE users SET `lvl`=`lvl`+1 WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1");
echo ' Поздравляем! Вы повысили уровень! ';
}
if($user['exp'] > 0 && preg_match("/^[1-9]{1,}0{2,}$/", $user['exp'])) {
mysql_query("UPDATE users SET `hpx`=`hpx`+1 WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1");
echo ' Вы повысили уровень энергии! ';
}
echo "<meta http-equiv=\"refresh\" content=\"2; URL=main.php\">";
}

  elseif($user['hp'] < 1 && $bot['hp'] > 0 && $bot['id'] != '') {
echo 'Вы проиграли!';
unset($_POST['boi']);
mysql_query("UPDATE users SET `battle`=0,  `lose`=`lose`+1 WHERE `id`='".((int)$user['id'])."'");
mysql_query("DELETE FROM `bots` WHERE  `for_id`='".((int)$user['id'])."' LIMIT 1");
mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('".((int)$user['id'])."', '".((int)$magictime)."');");

  echo "<meta http-equiv=\"refresh\" content=\"2; URL=main.php\">";
  }
}
elseif(!isset($_POST['block']) && $user['battle']> 0) {
echo ' Блок или удар не выбран! ';
}



if(isset($_POST['ext'])) {
session_unset();
session_destroy();
echo "<meta http-equiv=\"refresh\" content=\"2; URL=main.php\">";
}
echo "<form method=post><input type=submit  name=ext value=Выход></form>";
echo "<form method=post action=main.php><input type=submit  value=Пополнить энергию></form>";
echo "<form method=post><input type=submit name=hint  value=Подсказка ></form>";
require_once "footer.html";
?>
