<?php
$order = new Order($_POST);

$result = Eshop::saveOrder($order);

if($result){
    echo ORDER_SAV_OK;
    header('Refresh: 3, url=catalog');
} else {
    echo ORDER_SAV_ERROR;
    exit;
}

