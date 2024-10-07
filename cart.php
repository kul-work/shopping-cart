<?php
session_start();

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($mode == '') { //add to cart - default
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if $_SESSION['cart'] is set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        $key = array_search($product_id, array_column($_SESSION['cart'], 'product_id'));

        if ($key !== false) {
            // If the product is already in the cart, update the quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            // If the product is not in the cart, add it as a new entry
            $product = $_POST['product'];
            $price = $_POST['price'];

            $cart_item = array(
                'product_id' => $product_id,
                'product' => $product,
                'price' => $price,
                'quantity' => $quantity
            );

            array_push($_SESSION['cart'], $cart_item);
        }
    }

    if ($mode == 'update') {
        $key = $_POST['key'];
        $quantity = $_POST['quantity'];

        if (isset($_SESSION['cart'][$key])) {
            // Update the quantity of the item in the cart
            $_SESSION['cart'][$key]['quantity'] = $quantity;

            // Calculate the new total price of the cart
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Return the new total price
            echo $total;
        }
    }

    if ($mode == 'email') { //save email to $_SESSION
        $_SESSION['cart-email'] = $_POST['email'];
    }

    if ($mode == 'note') { //save order details to $_SESSION - incoming letter-by-letter
        $_SESSION['cart-note'] = $_POST['note'];
    }

}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if ($mode == 'remove') {
        if (isset($_GET['id'])) {
            $key = $_GET['id'];
            if (isset($_SESSION['cart'][$key])) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array

                // Return a success message or any other response if required
                echo 'ok';
            }
        }
    }

    if ($mode == 'empty') {
        //unset($_SESSION['cart-email']);
        unset($_SESSION['cart-note']);
        unset($_SESSION['cart']);
    }
}
?>
