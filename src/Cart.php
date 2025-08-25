<?php

require_once '../config/config.php';

class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    public function add($productId, $productName, $price, $quantity) {
        $key = array_search($productId, array_column($_SESSION['cart'], 'product_id'));

        if ($key !== false) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $cart_item = array(
                'product_id' => $productId,
                'product' => $productName,
                'price' => $price,
                'quantity' => $quantity
            );
            array_push($_SESSION['cart'], $cart_item);
        }
    }

    public function update($key, $quantity) {
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
        }
    }

    public function remove($key) {
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
        }
    }



    public function emptyCart() {
        $_SESSION['cart'] = array();
        unset($_SESSION['cart-note']);
    }

    public function getCartContents() {
        return $_SESSION['cart'];
    }

    public function getTotalPrice() {
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }
        return $total_price;
    }

    public function getTotalItems() {
        $total_items = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items += $item['quantity'];
        }
        return $total_items;
    }

    public function saveEmail($email) {
        $_SESSION['cart-email'] = $email;
    }

    public function saveNote($note) {
        $_SESSION['cart-note'] = $note;
    }
}
