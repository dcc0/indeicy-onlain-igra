<?php
require_once 'header.html';
?>
</br><tt>BOCCTAH. AKK.<tt></br>
Введите имя игрока:<br/>
<form  method="POST" action=remindmepassword.php>
<input type="text" name="login" size="40%" maxlength="1000"><br/>
Дата рождения:<br/>
<input type="text" name="borndate" size="40%" maxlength="1000">
<input type="submit" name="postlogin" value="Отправить">
</form>
<br/>

<?php
require 'config.php';
if (isset($_POST['postlogin']) && $_POST['login'] != ''  &&  $_POST['borndate'] !='') {
$inf=mysql_fetch_assoc(mysql_query("SELECT `id` FROM `users`  WHERE `login`='".$_POST['login']."'  LIMIT 1;")) or exit('<strong>HET...</strong>');
$anketa=mysql_fetch_assoc(mysql_query("SELECT  * FROM `anketa`  WHERE `for_id`='".$inf['id']."'  LIMIT 1;"));

if ($anketa['mail']!='') {
$to=$anketa['mail'];
$subject="Новый пароль: ";

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if ($anketa['borndate'] == $_POST['borndate'] ) {
$message=randomPassword();
mysql_query("UPDATE users SET `phrase`='".md5($message)."'  WHERE `id`='".((int)$inf['id'])."'");
mail($to, $subject, $message, $headers);
echo " Телеграмма в пути... ";
echo "<meta http-equiv=\"refresh\" content=\"3\">";
		}
		else
		{
		echo 'Неверная дата рождения!';
		}
	}

}
?>

<?php
require_once 'footer.html';
?>
