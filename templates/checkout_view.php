<main>
    <section class="cart-container">
        <h2>Checkout</h2>

        <?php
        if (isset($cart_items) && count($cart_items) > 0) {
            if (!empty($email)) echo '<p> &nbsp; <strong>Email:</strong> ' . $email . '</p>';

            echo '<table id="cart-table">';
            echo '<tr>';
            echo '<th>Product</th>';
            echo '<th>Price</th>';
            echo '<th>Quantity</th>';
            echo '<th>Total</th>';
            echo '</tr>';

            foreach ($cart_items as $item) {
                echo '<tr>';
                echo '<td width="100%">' . $item['product'] . '</td>';
                echo '<td nowrap>' . number_format($item['price'], 2) . CURRENCY . '</td>';
                echo '<td width="200" align="right">' . $item['quantity'] . '</td>';
                echo '<td nowrap>' . number_format($item['price'] * $item['quantity'], 2) . CURRENCY . '</td>';
                echo '</tr>';
            }

            echo '<tr>';
            echo '<td colspan="3" align="right">Total</td>';
            echo '<td>' . number_format($total, 2) . CURRENCY . '</td>';
            echo '</tr>';
            echo '</table>';

            if (!$success) {
                if (!empty($note)) echo '<p> &nbsp; <strong>Order details:</strong> ' . $note . '</p>'; ?>
                <center>
                    <form method="POST" action="checkout.php">
                        <input id="spam" name="spam" type="text" value="" placeholder="Anti-spam: <?= $antispam_question ?> is?" title="Enter the answer in digits. Like: '45'" />
                        <input type="hidden" id="confirm" name="confirm" value="1" />
                        <input type="hidden" id="email" name="email" value="<?= $email ?>" />
                    </form>
                    <button id="checkoutBtn2">Confirm Order</button>
                </center>
            <?php }
        } else {
            if (!$success) {
                echo '<p>Your cart is empty.</p>';
            }
        }

        if ($success) {
            if (!empty($order_id))
                echo "<center><p>Order <strong>ID $order_id</strong> has been placed successfully. Check email for details &amp; receipt.<br><br><em>Thank you for shopping with us!</em></p></center>";
        }
        ?>

        <br>
        <center><a href="index.php">[Back to Shopping]</a></center>

    </section>
</main>

<script>
    $(document).ready(function() {
        $('#checkoutBtn2').click(function() {
            var spam = $('#spam').val();
            if (spam == '') {
                alert('Answer the anti-spam question.');
            } else {
                $('form').submit();
            }
        });
    });
</script>
