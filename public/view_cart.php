<?php
require_once __DIR__ . '/../config/config.php';
session_start();

require_once '../src/Cart.php';
require_once '../src/Database.php';

$cart = new Cart();

$cart_items = $cart->getCartContents();
$total = $cart->getTotalPrice();

$note = isset($_SESSION['cart-note']) ? $_SESSION['cart-note'] : '';
$email = isset($_SESSION['cart-email']) ? $_SESSION['cart-email'] : '';

// Include the header template
include '../templates/header.php';

// Include the cart view template
include '../templates/cart_view.php';

// Include the footer template
include '../templates/footer.php';
?>
