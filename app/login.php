<?php
$user = new User($_POST);

if(Eshop::userCheck($user)){
    $_SESSION['admin'] = 1;
    header('Location: /admin');
    exit;
} 
echo USER_LOGIN_ERROR;
header('Refresh: 3, url=login');
exit;