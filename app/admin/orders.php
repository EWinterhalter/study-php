<?php
$orders = Eshop::getOrders();
if (!($orders instanceof Iterator)) {
    echo ORDER_SHOW_ERROR;
    throw new Exception('Ошибка в коде');
    exit;
}
if ($orders instanceof EmptyIterator) {
    echo ORDER_SHOW_ERROR;
    exit;
}
?>

<h1>Поступившие заказы:</h1>
<a href='/admin'>Назад в админку</a>
<hr>

<?php
foreach ($orders as $order) {
    echo "<h2>Заказ номер: {$order->id}</h2>";
    echo "<p><b>Заказчик</b>: {$order->customer_name}</p>";
    echo "<p><b>Email</b>: {$order->customer_email}</p>";
    echo "<p><b>Телефон</b>: {$order->customer_phone}</p>";
    echo "<p><b>Адрес доставки</b>: {$order->delivery_address}</p>";
    echo "<p><b>Дата размещения заказа</b>: {$order->order_date}</p>";

    echo "<h3>Купленные товары:</h3>";
    echo "<table>
        <tr>
            <th>N п/п</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена, руб.</th>
            <th>Количество</th>
        </tr>";

    $items = $order->items; // Предполагается, что это список товаров в заказе
    $totalPrice = 0;
    $itemCount = 1;

    foreach ($items as $item) {
        echo "<tr>
            <td>{$itemCount}</td>
            <td>{$item->title}</td>
            <td>{$item->author}</td>
            <td>{$item->pubyear}</td>
            <td>{$item->price}</td>
            <td>{$item->quantity}</td>
        </tr>";
        $totalPrice += $item->price * $item->quantity;
        $itemCount++;
    }

    echo "</table>";
    echo "<p>Всего товаров в заказе на сумму: {$totalPrice} руб.</p>";
    echo "<hr>";
}
?>