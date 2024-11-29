<?php
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1;"));
if ($user['fiz'] == 0 && $user['battle'] > 0) header("Location: main.php");
if ($user['fiz'] > 0 && $user['battle']==1) header("Location: zayavka.php");
require_once 'header.html';
$anketa=mysql_fetch_assoc(mysql_query("SELECT * FROM `anketa`  WHERE `for_id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
if($user['id']!=$anketa['for_id'])  {
   mysql_query("INSERT INTO `anketa` (`id`, `for_id`, `anketa`) values (NULL, '".((int)$_SESSION['uid'])."', 'Ваш текст');") or exit ('Ошибка записи!');
}

 if(isset($_POST['edit']) && $_POST['text']!='' && strlen($_POST['text']) < 50145) {
$text = htmlspecialchars($_POST['text'],ENT_QUOTES, "UTF-8");
mysql_query("UPDATE `anketa` SET `anketa` = '".mysql_real_escape_string($text)."' WHERE `for_id` = '".((int)$_SESSION['uid'])."' LIMIT 1;");
echo 'Записываем...<br/>';
echo "<meta http-equiv=\"refresh\" content=\"3; URL=anketa.php\">";
 }

 if(isset($_POST['obnov']) && $_POST['avatar']!='' && strlen($_POST['avatar']) < 2001) {
$avatar =$_POST['avatar'];
if (preg_match('#^http:\/\/(.*)\.(gif|png|jpg)$#i', $avatar) && $user['lvl'] > 0 ||
preg_match('#^https:\/\/(.*)\.(gif|png|jpg)$#i', $avatar) && $user['lvl'] > 0
) {
mysql_query("UPDATE `anketa` SET `avatar` = '".mysql_real_escape_string($avatar)."' WHERE `for_id` = '".((int)$_SESSION['uid'])."' LIMIT 1;");
echo ' Обновляем...<br/>';
echo "<meta http-equiv=\"refresh\" content=\"3; URL=anketa.php\">";
 }
 else {
echo 'Неправильная ссылка или персонаж 0-го уровня!';
 }
 }



 if(isset($_POST['sub_mail']) && $_POST['mymail']!='' && strlen($_POST['mymail']) < 1001) {
$mymail =$_POST['mymail'];
if (preg_match("|^([a-z0-9_.-]{1,20})@([a-z0-9.-]{1,20}).([a-z]{2,4})|is", strtolower($mymail)) && $user['lvl'] > 0) {
mysql_query("UPDATE `anketa` SET `mail` = '".mysql_real_escape_string($mymail)."' WHERE `for_id` = '".((int)$_SESSION['uid'])."' LIMIT 1;");
echo ' Добавляем...<br/>';
echo "<meta http-equiv=\"refresh\" content=\"3; URL=anketa.php\">";
 }
 else {
echo 'Неправильный адрес или персонаж ниже 3-го уровня!';
 }
}

 if(isset($_POST['sub_borndate']) && $_POST['borndate']!='') {
mysql_query("UPDATE `anketa` SET `borndate` = '".$_POST['borndate']."' WHERE `for_id`='".((int)$_SESSION['uid'])."'");
echo "<script>alert('Добавляем дату рождения...');</script><br/>";
echo "<meta http-equiv=\"refresh\" content=\"3; URL=anketa.php\">";
}

 if(isset($_POST['sub_change_pass']) && $_POST['change_pass']!='') {
$_POST['change_pass']=md5($_POST['change_pass']);
mysql_query("UPDATE `users` SET `phrase` = '".$_POST['change_pass']."' WHERE `id`='".((int)$_SESSION['uid'])."'");
echo "<script>alert('Меняем пароль...');</script><br/>";
echo "<meta http-equiv=\"refresh\" content=\"3; URL=anketa.php\">";
}
?>
<form method="post" action="main.php">
<input type="submit"  value="Вернуться">
</form>

Ваша анкета(примерно 12 000 слов макс.)
<form  method="POST">
<textarea  name="text" cols="60" rows="10"  maxlength="50144"><?=strip_tags($anketa['anketa'], '<i><tt><strong><a><img>');?></textarea>
<br/>
<button name="edit"  />Записать</button>
</form>
Ссылка на аватар</br>
<form  method="POST">
<input type="text" name="avatar" size="40%" maxlength="2000" value="100x100px">
<br/>
<button name="obnov"  />Обновить</button>
</form>
<form  method="POST">
Введите дату рождения в таком формате: 27121997<br/>
(используется для восстановления пароля)</br>
<input type="text" name="borndate" size="40%" maxlength="1000" value="">
<br/>
<button name="sub_borndate"  />Добавить</button>
</form>
Ваш email <br/>
<form  method="POST">
<input type="email" name="mymail" size="40%" maxlength="1000" value="<?=$anketa['mail']?>">
<br/>
<button name="sub_mail"  />Добавить</button>
</form>
Поменять пароль <br/>
<form  method="POST">
<input type="text" name="change_pass" size="40%" maxlength="1000" value="">
<br/>
<button name="sub_change_pass"  />Добавить</button>
</form>

<?php
if($anketa['avatar']!='') {
echo '<br/><img src="'.$anketa['avatar'].'" width="70" height="70"><br/>';
}
require_once 'footer.html';
?>
