<?php
//Crafted by MoLoТ (mol0t@list.ru) aka dcc0 aka iv777 aka BlackRus (Ivan Gavryushin 2017-2024)
//Для реализации этого движка я черпал вдохновение в движке БК1(2002). Но движок написан с чистого листа.
error_reporting(0); 
require_once 'header.html';
require_once 'config.php';
$logo=array('logo.gif', 'logo2.gif'); 
?><img src=./i/<?=$logo[rand(0,0)]?> title="Индейцы онлайн - браузрная игра" alt="Индейцы онлайн - браузрная игра"><br/>
<strong>Индейцы(а также викинги, рыцари, самураи) Онлайн;</strong>
<br/> __&#9876;__<br/>
&#9822; &#9821; &#9820;
<br/>Вход в игру 
<form name="test" method="post" action="enter.php"><br/>
Имя <br/><input type="text" name="login" size="30"><br/>
Пароль <br/><input type="password" name="phrase" size="30"><br/>
<input type="submit" name="sub" value="Вход">
</form>
<a href=remindmepassword.php>Забыли пароль?!</a>
<form name="reg" method="post" action="register.php"><br/>
<input type="submit" name="register" value="регистрация">
</form>
<form name="con" method="post" action="ind.php" target="_blank"><br/>
<input type="submit" name="con" value="Вход для консольных браузеров">
</form>
<br/>



<!-- Top.Mail.Ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3582950", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = "https://top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "tmr-code");
</script>
<noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3582950;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
<!-- /Top.Mail.Ru counter -->
<!-- Top.Mail.Ru logo -->
<a href="https://top-fwz1.mail.ru/jump?from=3582950">
<img src="https://top-fwz1.mail.ru/counter?id=3582950;t=465;l=1" height="31" width="88" alt="Top.Mail.Ru" style="border:0;" /></a>
<!-- /Top.Mail.Ru logo -->

<a href="https://money.yandex.ru/to/41001287710846" target="_blank"><tt>Пожертвовать проекту</tt></a><br/>
Изображения предоставлены <a href="https://pixabay.com/ru/users/OpenClipartVectors-30363/" target="_blank"> OpenClipartVectors </a>
<?php
echo "<br/>Игру можно <a href=\"https://cloud.mail.ru/public/15Eh/8xo4U41rK\">скачать</a> ";
require_once 'footer.html';
?>
