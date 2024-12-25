CREATE DATABASE IF NOT EXISTS eshop;
USE eshop;

DROP TABLE IF EXISTS catalog;
CREATE TABLE catalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    price INT,
    pubyear YEAR
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id CHAR(50) UNIQUE,
    customer VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS ordered_items;
CREATE TABLE ordered_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id CHAR(50),
    item_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (item_id) REFERENCES catalog(id)
);

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

DROP PROCEDURE IF EXISTS spAddItemToCatalog;
CREATE PROCEDURE spAddItemToCatalog (
    IN p_title VARCHAR(255),
    IN p_author VARCHAR(255),
    IN p_price INT,
    IN p_pubyear YEAR
)
BEGIN
    INSERT INTO catalog (title, author, price, pubyear)
    VALUES (p_title, p_author, p_price, p_pubyear);
END;

DROP PROCEDURE IF EXISTS spGetItemsFromCatalog;
CREATE PROCEDURE spGetItemsFromCatalog()
BEGIN
    SELECT * FROM catalog;
END;

DROP PROCEDURE IF EXISTS spGetItemsFromBasket;
CREATE PROCEDURE spGetItemsFromBasket(IN item_ids VARCHAR(255))
BEGIN
    SELECT id, title, author, price, pubyear
    FROM catalog 
    WHERE FIND_IN_SET(id, item_ids);
END;

DROP PROCEDURE IF EXISTS spSaveOrder;
CREATE PROCEDURE spSaveOrder(
    IN p_order_id CHAR(50),
    IN p_customer VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(20),
    IN p_address VARCHAR(255)
)
BEGIN
    INSERT INTO orders (order_id, customer, email, phone, address)
    VALUES (p_order_id, p_customer, p_email, p_phone, p_address);
END;

DROP PROCEDURE IF EXISTS spSaveOrderedItems;
CREATE PROCEDURE spSaveOrderedItems(
    IN p_order_id CHAR(50),
    IN p_item_id INT,
    IN p_quantity INT
)
BEGIN
    INSERT INTO ordered_items (order_id, item_id, quantity)
    VALUES (p_order_id, p_item_id, p_quantity);
END;

DROP PROCEDURE IF EXISTS spGetOrders;
CREATE PROCEDURE spGetOrders()
BEGIN
    SELECT order_id AS id, customer, email, phone, address, 
           UNIX_TIMESTAMP(created) AS `date`
    FROM orders;
END;

DROP PROCEDURE IF EXISTS spGetOrderedItems;
CREATE PROCEDURE spGetOrderedItems(IN p_order_id VARCHAR(50))
BEGIN
    SELECT title, author, price, pubyear, quantity
    FROM catalog
    INNER JOIN ordered_items
    ON catalog.id = ordered_items.item_id
    WHERE ordered_items.order_id = p_order_id;
END;


DROP PROCEDURE IF EXISTS spSaveAdmin;
CREATE PROCEDURE spSaveAdmin(
    IN u_login VARCHAR(255),
    IN u_password VARCHAR(255),
    IN u_email VARCHAR(255)
)
BEGIN
    INSERT INTO admins (login, password, email)
    VALUES (u_login, u_password, u_email);
END;


DROP PROCEDURE IF EXISTS spGetAdmin;
CREATE PROCEDURE spGetAdmin(
    IN u_login VARCHAR(255)
)
BEGIN
    SELECT id, login, password AS hash, email
    FROM admins
    WHERE login = u_login;
END;
