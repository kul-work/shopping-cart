<?php
session_start();

require_once '../src/Cart.php';

$cart = new Cart();

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

switch ($mode) {
    case 'add':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $cart->add($product_id, $product_name, $price, $quantity);
        }
        break;

    case 'update':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $key = $_POST['key'];
            $quantity = $_POST['quantity'];
            $cart->update($key, $quantity);
            echo $cart->getTotalPrice();
        }
        break;

    case 'remove':
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_GET['id'])) {
                $key = $_GET['id'];
                $cart->remove($key);
                echo 'ok';
            }
        }
        break;

    case 'empty':
        $cart->emptyCart();
        break;

    case 'email':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cart->saveEmail($_POST['email']);
        }
        break;

    case 'note':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cart->saveNote($_POST['note']);
        }
        break;

    default:
        // Default action: add to cart
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $cart->add($product_id, $product_name, $price, $quantity);
        }
        break;
}
?>
