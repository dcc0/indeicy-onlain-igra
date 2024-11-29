<?php
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."' LIMIT 1;"));
if ($user['fiz'] == 0 && $user['battle'] > 0) header("Location: main.php");
if ($user['fiz'] == 1 ) header("Location: fight.php");
require_once 'header.html';

echo "<meta http-equiv=\"refresh\" content=\"30; URL=stena_frame.php\">";
?>


<?php

$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='".((int)$_SESSION['uid'])."'  LIMIT 1;"));
$chat=mysql_query("SELECT * FROM `chat`  ORDER BY id DESC LIMIT 30;");


while($val = mysql_fetch_array($chat)) { 
echo '<strong><a href=info.php?login='.$val['login'].' target=_blank>'. $val['login'] . ' </a></strong> ' . $val['text'] . '<br/>';
}


require_once 'footer.html';
?>
