<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)$_GET['id'];
    Basket::init();
    $result = Eshop::addItemToBasket($id);
    if ($result) {
        echo BASKET_ITEM_ADD_OK; 
        header('Refresh: 3; url=add_item_to_basket.php'); 
    } else {
        echo BASKET_ITEM_ADD_ERROR; 
        exit; 
    }
} else {
    echo 'Добавление товара в корзину покупателя';
}