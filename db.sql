-- Drop the database if it exists
DROP DATABASE IF EXISTS shopping_cart;

-- Create the database
CREATE DATABASE shopping_cart;

-- Use the newly created database
USE shopping_cart;

-- Create the products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT
);

-- Create the orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(200) DEFAULT NULL,
    note TEXT
);

-- Create the order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample products
INSERT INTO products (name, price, description) VALUES
('Product 1', 10.00, 'Description for Product 1'),
('Product 2', 20.00, 'Description for Product 2');
