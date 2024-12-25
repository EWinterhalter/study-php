
<h1>Каталог товаров</h1>
<p class='admin'><a href='admin'>админка</a></p>
<p>Товаров в <a href='basket'>корзине</a>: <?php
echo Eshop::countItemsInBasket(); 
?>
</p>
<table>
<tr>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>В корзину</th>
</tr>

<?php
$books = Eshop::getItemsFromCatalog();
if (!($books instanceof Iterator)){
	echo CATALOG_SHOW_ERROR;
	throw new Exception("Ошибка");
	exit;
}
if($books instanceof EmptyIterator){
	echo CATALOG_SHOW_ERROR;
}
foreach ($books as $book) {
    echo "<tr>
    <td> {$book->title}</td>
    <td> {$book->author}</td>
    <td> {$book->pubyear}</td>
    <td> {$book->price} </td>
	<td><a href='add_item_to_basket?id={$book->id}'>Добавить</a></td> 
	</tr>";
}
?>
</table>