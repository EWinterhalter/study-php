<?php
error_reporting(E_ALL);

const CATALOG_ITEM_ADD_OK="Товар добавлен в каталог";
const CATALOG_ITEM_ADD_ERROR="Ошибка добавления товара в каталог";
const CATALOG_SHOW_ERROR="Ошибка при показе каталога";
const BASKET_ITEM_ADD_OK="Товар добавлен в корзину";
const BASKET_ITEM_ADD_ERROR="Ошибка добавления товара в корзину";
const BASKET_SHOW_ERROR="Ошибка при отображении корзины";
const BASKET_SHOW_NULL="Корзина пуста";
const BASKET_ITEM_DEL_OK="Удаление товара";
const BASKET_ITEM_DEL_ERROR="Ошибка при удалении товара";
const ORDER_SAV_OK ="Заказ сохранен";
const ORDER_SAV_ERROR ="Ошибка при сохранении заказа";
const ORDER_SHOW_ERROR ="Ошибка отображения заказа";
const USER_ADD_D = "Пользователь уже существует";
const USER_ADD_OK = "Пользователь добавлен";
const USER_ADD_ERROR = "Ошибка добавления пользователя";
const USER_LOGIN_ERROR = "Ошибка входа";

require_once 'core/init.php';

require_once 'app/__header.php';
require_once 'app/__router.php';
require_once 'app/__footer.php';
