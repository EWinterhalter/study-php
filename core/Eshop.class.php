<?php
class Eshop {
    private static $db = null;
    public static function init(array $db){
        self::$db = new PDO("mysql:host={$db['HOST']};dbname={$db['NAME']}", $db
        ['USER'], $db['PASS']);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    public static function addItemToCatalog(Book $item): bool{
        self::cleanItem($item);
        $params = "{$item->title}, {$item->author}, {$item->price},
        {$item->pubyear}";
        $sql="Call spAddItemToCatalog($params)";
        return (bool) self::$db->exec($sql);
    }
    public static function getItemsFromCatalog(): iterable {
        $sql = "Call spGetItemsFromCatalog()";
        $result = self::$db->query($sql, PDO::FETCH_CLASS, 'Book');
        if(!$result) return new EmptyIterator();
        return new IteratorIterator($result);
    }
    public static function countItemsInBasket(){
        return Basket::size();
    }
    public static function addItemToBasket(int $id): bool {
           $id = Cleaner::uint($id);
           if(!$id)
                return false;
            Basket::add($id);
            return true;

    }
    public static function getItemsFromBasket(): iterable {
        if(!self::countItemsInBasket())
            return new EmptyIterator();
        $keys = array_keys(iterator_to_array(Basket::get()));
        $ids = implode(',', $keys);
        $sql = "Call spGetItemsFromBasket('$ids')";
        $stmt = self::$db->query($sql);
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
        if(!count($books))
            return new EmptyIterator();
        foreach($books as $book){
            $book->quantity = Basket::quantity($book->id);
        }
        return new ArrayIterator($books);
    }
    
    
    public static function removeItemFromBasket($id){
        {
            $id = Cleaner::uint($id);
            if(!$id)
                return false;
            Basket::remove($id);
            return true;
        }

    }
    public static function saveOrder(Order  $order){
        self::cleanOrder($order);
        $order->id=Cleaner::str2db(Basket::getOrderId(), self::$db);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db->beginTransaction();
        try {
            $params = "{$order->id}, {$order->customer}, {$order->email}, {$order->phone},
            {$order->address}";
            $sql = "Call spSaveOrder($params)";
            self::$db->exec($sql);
            foreach(Basket::get() as $itemId => $quantity){
                $params = "{$order->id}, $itemId, $quantity";
                $sql = "Call spSaveOrderedItems($params)";
                self::$db->exec($sql); 
            }
            self::$db->commit();
            Basket::clear();
        } catch(PDOException $e){
            self::$db->rollBack();
            trigger_error($e->getMessage());
            return false;
        }
    }

    public static function getOrders():Iterator{
        $sql = "Call spGetOrder()";
        $stmt = self::$db->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
        if(!count($orders)) return new EmptyIterator();
        $stmt->closeCursor();
        foreach($orders as $order){
            $sql = "Call spGetOrderedItems('{$order->id}')";
            $stmt = self::$db->query($sql);
            $order->items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');

        }
        return new ArrayIterator($orders);
    }

    private static function userGet(User $user): User
    {
        self::cleanUser($user);
        $sql = "CALL spGetAdmin(:login)";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':login' => $user->login]);
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');

        if (!count($result)) return $user;
        $dbUser = $result[0];
        $dbUser->password = $user->password; 
        return $dbUser;
    }
    public static function userAdd(User $user, string $password): bool
    {
        $user = self::userGet($user);
        if ($user->id) return false; 

        $user->password_hash = self::createHash($password);
        $sql = "CALL spSaveAdmin(:login, :password_hash, :email)";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([
            ':login' => $user->login,
            ':password_hash' => $user->password_hash,
            ':email' => $user->email
        ]);
    }

    public static function userCheck(User $user): bool
{
    $dbUser = self::userGet($user);
    if (!$dbUser->id) return false; 
    if (empty($dbUser->password_hash)) {
        return false;
    }
    return password_verify($user->password, $dbUser->password_hash);
}

    public static function isAdmin(): bool {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public static function logIn(User $user): bool {
        if (self::userCheck($user)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['is_admin'] = $user->is_admin;
            return true;
        }
        return false;
    }

    public static function logOut() {
        session_unset();
        session_destroy();
    }
    private static function createHash(string $password): string{
        return password_hash($password, PASSWORD_DEFAULT);
    }
    private static function cleanItem(Book $item){
        $item->title = Cleaner::str2db($item->title, self::$db);
        $item->author = Cleaner::str2db($item->author, self::$db);
        $item->price = Cleaner::str2db($item->price, self::$db);
        $item->pubyear = Cleaner::str2db($item->pubyear, self::$db);
    }
    private static function cleanOrder(Order $item){
        $item->customer = Cleaner::str2db($item->customer, self::$db);
        $item->phone = Cleaner::str2db($item->phone, self::$db);
        $item->email = Cleaner::str2db($item->email, self::$db);
        $item->address = Cleaner::str2db($item->address, self::$db);
    }
    private static function cleanUser(User $item){
        $item->login = Cleaner::str($item->login);
        $item->password = Cleaner::str($item->password);
        $item->email = Cleaner::str($item->email);
    }

}

