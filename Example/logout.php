<?php
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
setcookie("loggedIn", "", time() - 60*60*24*30);
header('Location: login.php');
exit;
