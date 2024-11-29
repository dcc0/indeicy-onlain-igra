<?php
//Crafted by MoLoТ (mol0t@list.ru) aka dcc0 aka iv777 aka BlackRus (Ivan Gavryushin 2017-2024)
//Для реализации этого движка я черпал вдохновение в движке БК1(2002). Но движок написан с чистого листа.
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
$user = mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='" . ((int) $_SESSION['uid']) . "' LIMIT 1;"));
if ($user['fiz'] == 0 && $user['battle'] > 0) header("Location: main.php");
if ($user['fiz'] == 0 && $user['battle'] == 0) header("Location: zayavka.php");
if ($user['online'] !=1 || $user['ontime'] < time()) {
$mytime = time() + 1200;
mysql_query("UPDATE users SET `online`=1, `ontime`='".((int)$mytime)."'  WHERE `id`= '{$user['id']}'");
}
require_once 'header.html';
echo 'В бою...';

$enemy     = mysql_fetch_assoc(mysql_query("SELECT * FROM `users`  WHERE `id`='" . ((int) $user['enemy_id']) . "'  LIMIT 1;"));
$battle    = mysql_fetch_assoc(mysql_query("SELECT * FROM `battle` WHERE `id`='" . ((int) $user['battle_n']) . "' LIMIT 1;"));


$me='<br/><img src="./i/obr.png">'.$user['login'].' (' . $user['lvl'] . ')<br/> Энергия ' . $user['hp'] . '/' .$user['hpx'].'<br/>';


$messf = array(
    ' - Вы выставляете блок! ',
    ' - Попадание ! ',
	' Блок или удар не выбран! ',
	' - Попадание по противнику ! ',
	' - Противник выставляет блок ! ',
	' Вероятность нанести критический удар -  ',
	' <strong>Бой окончен! Ничья!</strong> ',
	' <strong>Вы победили!</strong> ',
	' Поздравляем! Вы повысили уровень! ',
	' Вы повысили количество энергии! ',
	' <strong>Вы проиграли!</strong> ',
	' Запрещенный символ! ',
	'<meta http-equiv="refresh" content="2">',
	'Увернулся от удара!',
	'Сумел выдержать критический удар!',

);

function bot_hit_me($enemy, $user, $hit, $messf) {
//Критический удар от бота
	if($enemy['npc'] > 0 ) {
	if (rand(7, 7) == 7) {
  $hit = $enemy['npc'] + 1;
  echo '<br/>' . $enemy['login'] . $messf['5'] . '<font color=red>' . $hit . '</font><br/>';
 }

 mysql_query("UPDATE `users` SET `hp` =`hp`-'".((int) $hit)."'  WHERE `id`= '" . ((int)$user['id']). "'");
	}
}


//Обмен ударами + c ботом
if (($user['id'] == $battle['t1'] && time() > $battle['timer']) || ($user['id'] == $battle['t2'] && time() > $battle['timer_2'])) {

if (isset($_POST['hit']) && $_POST['attack'] && $_POST['block']) {
    $hit = 1;
    if ($user['id'] == $battle['t1']) {
		  $battle['t2_b']=rand(1,2);
		   $battle['t2_h']=rand(1,2);
        if ($battle['t1_b'] != $battle['t2_h']) {

			bot_hit_me($enemy, $user, $hit, $messf);

			$messmin = $messf['1'];
        } else {
			$messmin = $messf['0'];
		}
    } elseif ($user['id'] == $battle['t2']) {
		  $battle['t1_b']=rand(1,2);
		   $battle['t1_h']=rand(1,2);
        if ($battle['t2_b'] != $battle['t2_h']) {
			bot_hit_me($enemy, $user, $hit, $messf);
			$messmin = $messf['1'];
        } else {
		    $messmin = $messf['0'];
		}
    }
}

//Обмен ударами с игроком или ботом
if (isset($_POST['hit']) && $_POST['attack'] && $_POST['block']) {
    $_POST['attack'] = (int) $_POST['attack'];
    $_POST['block']  = (int) $_POST['block'];
    $timer           = time() + 5;
    $timer           = (int) $timer;
    if ($user['id'] == $battle['t1']) {
        mysql_query("UPDATE battle SET `t1_h`='" . (int) $_POST['attack'] . "', `t1_b`='" . (int) $_POST['block'] . "', `timer`='" . (int) $timer . "'   WHERE `id`= '" . (int) $user['battle_n'] . "'");
        if ($battle['t1_h'] != $battle['t2_b']) {

            mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`= '" . (int) $enemy['id'] . "'");
			$messp = $messf['3'];
			//Вероятность критического удара
			$special_hit=rand(1, 7);
	$battcom='0';
		//Критический удар
		  if ($special_hit == 7)  {
	$battcom='1';
		//Шанс увернуться от критического удара
		$chance_to_avoid_hit =rand(1, 3);
		if ($chance_to_avoid_hit == 3){
	$battcom='2';
		 $messp = $messf['14'] . '<font color=green> -1 </font><br/>';
		} else {
	$battcom='3';
		 $messp = $messf['5'] . '<font color=red> -2 </font><br/>';
		 mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`= '" . (int) $enemy['id'] . "'");
		 }
		}
		//Увернулся от удара
		if ($special_hit == 6)  {
	$battcom='4';
                $messp = $messf['13'] . '<font color=green> -0 </font><br/>';
                mysql_query("UPDATE `users` SET `hp` =`hp`+1  WHERE `id`= '" . (int) $enemy['id'] . "'");
                }


        }
        else {
		$messp = $messf['4'];
		}
    } else {
        mysql_query("UPDATE battle SET `t2_h`='" . (int) $_POST['attack'] . "', `t2_b`='" . (int) $_POST['block'] . "', `timer_2`='" . (int) $timer . "'   WHERE `id`= '" . (int) $user['battle_n'] . "'");
        if ($battle['t2_h'] != $battle['t1_b']) {
            mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`= '" . (int) $enemy['id'] . "'");
			$messp = $messf['3'];

        }
		else {
		$battcom='0';
		$messp = $messf['4'];
		}
    }
} elseif (isset($_POST['hit'])) {
   echo $messf['2'];
}
echo $me;
//Автовыбор удара и блока
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

$user2 = mysql_fetch_assoc(mysql_query("SELECT `hp` FROM `users`  WHERE `id`='" . ((int) $_SESSION['uid']) . "' LIMIT 1;"));

//форма с разменом

    echo '<p><a name="here"></a></p><br/>';
    echo '<form method="post" action="#here">
	Удар <tt><i>' .$messp.'</i></tt>   <br/><tt>Верхн. часть: </tt><INPUT TYPE=radio  NAME=attack value=1 '.$a1.'>
	<br/><tt>Нижн. часть:</tt><INPUT TYPE=radio  NAME=attack value=2 '.$a2.'><br/>';
     //if (rand(0,2)==2) {
       //echo '<img src="i/Untitled.gif " width="17%" height="17%"><br/>';
//}
//Комментарий к битве. Удалим цифры


	echo 'Блок  <tt><i>' .$messmin.'</i></tt><tt><i>' . $themess . '</i></tt>
	<br/><tt>Верхн. часть:</tt> </tt><INPUT TYPE=radio  NAME=block value=1 '.$a3.'>
	<br/><tt>Нижн. часть:</tt><INPUT TYPE=radio  NAME=block value=2 '.$a4.'><br/>
	<input type="submit" name="hit" value="Вперед">
	Клич: <img src="./i/timer.gif?' . rand(1, 10000) . '">
	<input type="text" name="text" size="16%" maxlength="30">
	<i> ' . $battle['com']  . ' </i>
	</form>';

} else {
	echo $me;
	unset($battle['t1_b']);
	unset($battle['t1_b']);
	unset($battle['t2_b']);
	unset($battle['t2_h']);
    echo '<a name="here"></a>';
    unset($_POST['hit']);
    echo ' <tt><i>' . $themess . '</i></tt> <br/>';
    echo ' 5 секунд на размышления!<br/>';
    echo '<i> ' . $battle['com'] . ' </i> ';
    echo '<meta http-equiv="refresh" content="5; URL=fight.php#here">';
    echo '<input type="button" onclick="window.location.reload()"  value ="Обновить" />';

}

echo '<br/><img src="./i/obr.png" >';
echo $enemy['login'];
echo '(' . $enemy['lvl'] . ')';
echo '<br/> Энергия ' . $enemy['hp'] . '/' . $enemy['hpx'] . '<br/>';



//Завершение битвы
$magictime = time() + 40;
//Если бой был с ботом
function bot_end($enemy) {
		if ($enemy['npc'] > 0) {
			mysql_query("UPDATE users SET `hp`='" . ((int) $enemy['hpx']) . "', `ontime`='".((int)time())."'  WHERE `id`='".((int)$enemy['id'])."'");
    }
}
//Ничья
if ((int) $user['hp'] <= 0 && (int) $enemy['hp'] <= 0) {
    echo $messf['6'];
    mysql_query("DELETE FROM `battle` WHERE  `id`='" . (int) $user['battle_n'] . "' LIMIT 1");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `draw`=`draw`+1  WHERE `id`= '" .((int)$enemy['id']). "'");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `draw`=`draw`+1  WHERE `id`= '" . (int) $user['id'] . "'");
    mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('" . ((int) $user['id']) . "', '" . ((int) $magictime) . "');");
	bot_end($enemy);
	echo $messf['12'];

}
//Победа
elseif ((int) $user['hp'] > 0 && (int) $enemy['hp'] <= 0) {
	echo $messf['7'];
    mysql_query("DELETE FROM `battle` WHERE  `id`='" . (int) $user['battle_n'] . "' LIMIT 1");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `lose`=`lose`+1  WHERE `id`= '" . (int) $enemy['id'] . "'");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `win`=`win`+1, `exp`=`exp`+1  WHERE `id`= '" . (int) $user['id'] . "'");
    mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('" . ((int) $user['id']) . "', '" . ((int) $magictime) . "');");
    if ($user['exp'] > 0 && preg_match("/^[0-9]{1,}0$/", $user['exp'])) {
        mysql_query("UPDATE users SET `lvl`=`lvl`+1 WHERE `id`='" . ((int) $_SESSION['uid']) . "' LIMIT 1");
		echo $messf['8'];

    }
    if ($user['exp'] > 0 && preg_match("/^[1-9]{1,}0{2,}$/", $user['exp'])) {
        mysql_query("UPDATE users SET `hpx`=`hpx`+1 WHERE `id`='" . ((int) $_SESSION['uid']) . "' LIMIT 1");
		echo $messf['9'];
    }
     if ($enemy['npc'] > 0) {
        mysql_query("UPDATE users SET `hp`='" . ((int) $enemy['hpx']) . "', `ontime`='".((int)time())."'  WHERE `id`= '" . (int) $enemy['id'] . "'");
		mysql_query("UPDATE users SET `exp`=`exp`+1  WHERE `id`= '" . (int) $user['id'] . "'");
    }
    echo $messf['12'];
}
//Поражение
elseif ((int) $user['hp'] <= 0 && (int) $enemy['hp'] > 0) {
	echo $messf['10'];

    mysql_query("DELETE FROM `battle` WHERE  `id`='" . (int) $user['battle_n'] . "' LIMIT 1");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `win`=`win`+1, `exp`=`exp`+1  WHERE `id`= '" . (int) $enemy['id'] . "'");
    mysql_query("UPDATE users SET `battle`=0, `fiz`=0, `battle_n`=0, `enemy_id`=0, `lose`=`lose`+1  WHERE `id`= '" . (int) $user['id'] . "'");
    mysql_query("INSERT INTO `eff` (`for_id`, `timer`) values ('" . ((int) $user['id']) . "', '" . ((int) $magictime) . "');");

    bot_end($enemy);
    echo $messf['12'];
}

//Запишем клич или сообщение битвы
//Проверим, установлен ли удар и введен ли текст игроком
if (isset($_POST['hit']) && $_POST['text']!='') {
    $_POST['text'] = substr($_POST['text'], 0, 150);
    //Проверим ввод
    if (!preg_match('/^[a-zа-яё0-9\.\,\!\?\-\+\s]+$/ui', $_POST['text'])) {
           $_POST['text']='Шум битвы!';
           $POST_TO=$_POST['text'];
           mysql_query("UPDATE battle SET `com`='" . mysql_real_escape_string($POST_TO) . "'  WHERE `id`= '" . ((int) $user['battle_n']) . "'");
           echo $messf['11'];
    } else {
        //Запишем текст игрока
        $_POST['text'] = $user['login'] . ':' . $_POST['text'];
         $POST_TO=$_POST['text'];
        $_POST['text'] = strip_tags($_POST['text']);
         $POST_TO=$_POST['text'];
        mysql_query("UPDATE battle SET `com`='" . mysql_real_escape_string($POST_TO) . "'  WHERE `id`= '" . ((int) $user['battle_n']) . "'");
    }
}


if (isset($_POST['hit']) && $_POST['text']=='') {
        //Если обычный размен и текста нет
        //Информируем  о крите через клич
	if ($battcom == '1' ) {
	    $_POST['text'] = "Вероятность крит. удара (<font color=red>-2?</font>) по ";
    	$POST_TO=$_POST['text'];
    	$POST_TO .=   $enemy['login'];
    	}
	 if ($battcom == '2' ) {
        $_POST['text'] = "Сдержал  крит. удар (<font color=green>-1</font>)  ";
        $POST_TO=$_POST['text'];
    	$POST_TO .=   $enemy['login'];
        }
	 if ($battcom == '3' ) {
        $_POST['text'] = "Крит. удар (<font color=red>-2</font>) по  ";
    	$POST_TO=$_POST['text'];
    	$POST_TO .=   $enemy['login'];
    	 }
	 if ($battcom == '4' ) {
        $_POST['text'] = "Увернулся от удара (<font color=green>-0</font>)  ";
        $POST_TO=$_POST['text'];
    	$POST_TO .=   $enemy['login'];
	     }
        if ($battcom == '0' ){
    	$POST_TO='Шум битвы!!!';
        }
    	mysql_query("UPDATE battle SET `com`='" . mysql_real_escape_string($POST_TO) . "'  WHERE `id`= '" . ((int) $user['battle_n']) . "'");

}
require_once 'footer.html';
?>
