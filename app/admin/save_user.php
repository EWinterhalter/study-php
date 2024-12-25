<?php
$user = new User($_POST);
$res = Eshop::userAdd($user, $password);

if($res instanceof User){
    echo USER_ADD_D;
    header('Refresh: 3, url=create.user.php');
    exit;   
}
if($res){
    echo USER_ADD_OK;
    header('Refresh: 3, url=create.user.php');
} else {
    echo USER_ADD_ERROR;
    exit;
}
