<?php

$user_id=$user['id'];
//Функция критического удара по боту
function krit_udar($user_id) {

                 //Вероятность критического удара
                $special_hit=rand(1, 7);
                //Критический удар
                  if ($special_hit == 7)  {
                //Шанс увернуться от критического удара
                $chance_to_avoid_hit =rand(1, 3);
                if ($chance_to_avoid_hit == 3){
                 echo  'Чучело сдержало крит. удар <font color=green> -1 </font><br/>';
                } else {
                 echo 'Крит. удар по чучелу <font color=red> -2 </font><br/>';
                 mysql_query("UPDATE `bots` SET `hp` =`hp`-1  WHERE `for_id`= '" . (int) $user_id . "'");
                 }
                }
                //Увернулся от удара
                if ($special_hit == 6)  {
                echo 'Чучело увернулось от  удара <font color=green> -0 </font><br/>';
                mysql_query("UPDATE `bots` SET `hp` =`hp`+1  WHERE `for_id`= '" . (int) $user_id . "'");
                }
}


//Функция критического удара по игроку от бота
function krit_udar_bit($user_id) {

                 //Вероятность критического удара
                $special_hit=rand(1, 7);
                //Критический удар
                  if ($special_hit == 7)  {
                //Шанс увернуться от критического удара
                $chance_to_avoid_hit =rand(1, 3);
                if ($chance_to_avoid_hit == 3){
                 echo  'Вы сдержали крит. удар <font color=green> -1 </font><br/>';
                } else {
                 echo 'Крит. удар по Вам <font color=red> -2 </font><br/>';
                  mysql_query("UPDATE `users` SET `hp` =`hp`-1  WHERE `id`='".((int)$_SESSION['uid'])."'");
                 }
                }
                //Увернулся от удара
                if ($special_hit == 6)  {
                echo 'Вы увернулись от удара <font color=green> -0 </font><br/>';
                 mysql_query("UPDATE `users` SET `hp` =`hp`+1  WHERE `id`='".((int)$_SESSION['uid'])."'");
                }
}



?>
