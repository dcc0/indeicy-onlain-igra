<?php
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
if ($user['fiz'] > 0) header("Location: fight.php");
if ($user['fiz'] == 0 && $user['battle'] > 0) header("Location: main.php");
require_once 'header.html';
echo $user['login'].'('.$user['lvl'].')<br/>';
echo 'В пути...<br/>';

function check_npc_battle() {
	mysql_query("UPDATE users SET `battle`='0', `fiz`='0', `battle_n`='0', `enemy_id`='0'  WHERE  `npc`=1 AND `battle`=1 AND `ontime` < '".((int)time())."' LIMIT 1");
}

function showloc($go) {
$plc=mysql_fetch_assoc(mysql_query("SELECT * FROM `world`  WHERE `id`='".((int)$go)."'  LIMIT 1;"));

if ($go==0) {
echo '<a href=go.php?go='.$go.'><img src=./i/l/'.$go.'.png width="100" heigth="100" alt="'.$plc['place'].'" title="'.$plc['place'].'"></a><br/>';
$go= $plc['place'];
}
elseif ($go > 21) {
$ms=rand(22,31);
$mss=array( 	"Вы блуждаете...",
		"Вы блуждаете в прериях...",
		"То лес, то степь, то скалы...",
		"Есть шанс что-нибудь раздобыть...",
	);

$spec_mess=array(31=>"Внезапно в расселине Вы обнаружили едва заметную дверь...");
//Повышаем уровень через прогулку
$user234=mysql_fetch_assoc(mysql_query("SELECT `lvl`  FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
if ($user234['lvl'] < 1 && $ms == 31){
print "Вы повысили уровень!  ";
 mysql_query("UPDATE `users` SET `lvl` =1   WHERE `id`='".(int)$_SESSION['uid']."'");
}


echo $mss[rand(0,count($ms))].'<br/>';
echo '<a href=go.php?go='.$go.'><img src=./i/l/'.$go.'.png alt="'.$plc['place'].'" title="'.$plc['place'].'"></a><br/>';
echo $spec_mess[$go] . '<br/>';
$go = $plc['place'];


}
 else {
 echo '<a href=#closed><img src=./i/l/'.$go.'.png  alt="Закрыто" title="Закрыто"></a><br/>';
$go = $plc['place'];
 }
return $go;
}

if(isset($_POST['sub2'])  ) {
$go=$_POST['go'];
	--$go;
	if($go < 1) {$go=0;}
	if($go > 20) {$go=rand(22,31);}
}
elseif(isset($_POST['sub1'])) {
$go=$_POST['go'];
	++$go;
if($go > 20) {$go=rand(22,31);}
}

$saved='';
if(isset($_POST['sub1']) && rand(1,3)==3 && $go==30) {
$_POST['go']=19;
$go=$_POST['go'];
$saved = '<br/>Вам повезло! Вы вышли на знакомую тропинку...';
}

if(!isset($_POST['go'])) {$go=0;}

?>
<form method="post" action="world.php">
<input type="hidden"  name="go" value="<?=$go?>">
<input type="submit"  name="sub2" value="Назад по тропинке">
</form>
<br/>
<form method="post" action="world.php">
<input type="hidden"  name="go" value="<?=$go?>">
<input type="submit"  name="sub1" value="Вперёд по тропинке">
</form>
<br/>

<?php
echo showloc($go);
if($go == 19 ) {
echo $saved;
}
if($go == 20 ) {
echo ' <tt> <br/>Вперёд по тропинке - непроходимый лес, а за ним степь. Тут легко заблудиться! </tt><br/> ';
}

if(mt_rand(1,5)==3 && $_POST['go'] > 8 && !isset($_POST['fight'])) {
$bot_rand=rand(0,3);
$enemy=mysql_fetch_assoc(mysql_query("SELECT `id`, `login` FROM `users`  WHERE `phrase`='".((int)$bot_rand)."' AND `battle`=0  LIMIT 1;"));

check_npc_battle();
if($enemy['id']!='') {
echo '<br/>Вы не один в этом глухом местечке.<br/> Напасть на: <br/>';
echo '<form method="post">
<input type="hidden"  name="name_en" value="'.$bot_rand.'">
<input type="submit" name="fight" value="'.$enemy['login'].'"></form>';
}
}

if(isset($_POST['fight']) && isset($_POST['name_en'])) {
$enemy=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `phrase`='".((int)$_POST['name_en'])."'  LIMIT 1;"));
if( (int)$enemy['hp'] < 5) {
echo ' <br/>Противник слишком ослаблен! ';
}
elseif( $user['hp'] < 5) {
echo ' <br/>Не хватает энергии для боя! ';
}
elseif( $enemy['fiz'] !=0 ) {
echo ' <br/>Противник уже в бою!  ';
}
elseif ($enemy['battle']==0 ) {
mysql_query("INSERT INTO `battle` (`id`, `t1`, `t2`, `t1_h`, `t2_h`, `t1_b`, `t2_b`) values (NULL, '".((int)$user['id'])."', '".((int)$enemy['id'])."' , '".mt_rand(1,2)."', '".mt_rand(1,2)."', '".mt_rand(1,2)."', '".mt_rand(1,2)."');");
$bat=mysql_fetch_assoc(mysql_query("SELECT * FROM `battle` WHERE `t1`='{$user['id']}' AND  `t2`='".((int)$enemy['id'])."' LIMIT 1;"));

mysql_query("UPDATE users SET `battle`=1, `fiz`=1, `online`=1, `battle_n`='".((int)$bat['id'])."', `enemy_id`='".($user['id'])."', `ontime`='".((int)time()+1200)."'  WHERE `id`= '".($enemy['id'])."'");
mysql_query("UPDATE users SET `battle`=1, `fiz`=1, `online`=1, `battle_n`='".((int)$bat['id'])."', `enemy_id`='".((int)$enemy['id'])."'  WHERE `id`= '".((int)$user['id'])."'");

echo ' <br/>Бой между ' .$user['login']. ' и ' .$enemy['login']. ' начался! ';
echo "<meta http-equiv=\"refresh\" content=\"2; URL=zayavka.php\">";
}
elseif($_POST['fight'] && $user['fiz']==1) {
echo ' <br/>Бой в разагаре! ';
}
else {
echo ' <br/>Персонаж  в бою! Или слишком ослаблен!';
	}
}

require_once 'footer.html';

?>
