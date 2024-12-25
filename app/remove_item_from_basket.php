<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    Basket::init();
    $id = (int)$_GET['id'];
    $result = Eshop::removeItemFromBasket($id);
    if ($result) {
        echo BASKET_ITEM_DEL_OK;
    } else {
    echo BASKET_ITEM_DEL_ERROR;
    }
}

