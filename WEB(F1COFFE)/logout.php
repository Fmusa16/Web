<?php
session_start();
session_unset();
session_destroy();
header('Location: /WEB(F1COFFE)/login.php');
exit;
