<?php
session_start();

require_once '../src/Database.php';
require_once '../src/Cart.php';

$db = new Database();
$conn = $db->getConnection();
$cart = new Cart();

$total_price = $cart->getTotalPrice();
$total_items = $cart->getTotalItems();

$sql = "SELECT id, name, description, price FROM products";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

// Include the header template
include '../templates/header.php';

// Include the product list template
include '../templates/product_list.php';

// Include the footer template
include '../templates/footer.php';
?>
