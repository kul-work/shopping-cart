<?php
require_once __DIR__ . '/../config/config.php';
session_start();

require_once '../src/Database.php';
require_once '../src/Cart.php';

$db = new Database();
$conn = $db->getConnection();
$cart = new Cart();

$note = isset($_SESSION['cart-note']) ? $_SESSION['cart-note'] : '';
$email = isset($_SESSION['cart-email']) ? $_SESSION['cart-email'] : '';
$spam = isset($_POST['spam']) ? $_POST['spam'] : '';

$order_id = '';
$success = false;
$antispam = array('two+three', 'four+five', 'nine+two');
$antispam_answer = array(5, 9, 11);
$antispam_question = $antispam[rand(0, 2)];


if (isset($_POST['confirm'])) {
    if (in_array($spam, $antispam_answer)) {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            // Generate a unique order ID
            $sql = "INSERT INTO orders (total_price, email, note) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $total = $cart->getTotalPrice();
            $email_sanitized = Database::sanitize($email);
            $note_sanitized = Database::sanitize($note);
            $stmt->bind_param("dss", $total, $email_sanitized, $note_sanitized);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            foreach ($_SESSION['cart'] as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                $stmt->execute();
            }
            $success = true;
        }
    }
}


if ($success) {
    $cart->emptyCart();
    unset($_SESSION['cart-email']);
}

$cart_items = $cart->getCartContents();
$total = $cart->getTotalPrice();

$conn->close();

// Include the header template
include '../templates/header.php';

// Include the checkout view template
include '../templates/checkout_view.php';

// Include the footer template
include '../templates/footer.php';
?>
