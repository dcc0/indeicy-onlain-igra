<?php
include 'checksession.php';
if (!($_SESSION['uid'] > 0)) header("Location: index.php");
require 'config.php';
if ($_GET['go'] == 0) header("Location: main.php");
if ($_GET['go']  > 0) header("Location: world.php");