<h1>Корзина</h1>
<table>
<tr>
    <th>№</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
    <th>Количество</th>
	<th>Удалить</th>
</tr>

<?php
Basket::init();
$books = Eshop::getItemsFromBasket();
if (!($books instanceof Iterator)){
    echo BASKET_SHOW_ERROR;
    throw new Exception("Ошибка");
    exit;
}
if($books instanceof EmptyIterator){
    echo BASKET_SHOW_NULL;
}

$totalPrice = 0; 
$itemNumber = 1; 

foreach($books as $book){
    $itemTotal = $book->price * $book->quantity; 
    $totalPrice += $itemTotal; 
    echo "<tr>
        <td>{$itemNumber}</td>
        <td>{$book->title}</td>
        <td>{$book->author}</td>
        <td>{$book->pubyear}</td>
        <td>{$book->price}</td>
        <td>{$book->quantity}</td>
        <td><a href='/remove_item_from_basket?id={$book->id}'>Удалить</a></td>
    </tr>";
    $itemNumber++; 
}

echo "<p>Всего товаров в корзине на сумму: {$totalPrice} руб.</p>";
?>
<div style="text-align:center">
    <input type="button" value="Оформить заказ!" onclick="location.href='/create_order'" />
</div>
