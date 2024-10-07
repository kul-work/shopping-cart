<?php
    session_start();
    include_once 'db.php';

    // $_SESSION is stronger
    $note = (isset($_POST['note'])) ? $_POST['note'] : '';
    $note = (isset($_SESSION['cart-note'])) ? $_SESSION['cart-note'] : $note;

    // $_POST is stronger
    $email = (isset($_SESSION['cart-email'])) ? $_SESSION['cart-email'] : '';
    $email = (isset($_POST['email'])) ? $_POST['email'] : $email;

    $spam = (isset($_POST['spam'])) ? $_POST['spam'] : '';

    $order_id = '';
    $success = false;
    $antispam = array('two+three','four+five','nine+two');
    $antispam_answer = array(5,9,11);

    if (isset($_POST['confirm']))
    if (in_array($spam, $antispam_answer))
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // Generate a unique order ID
        $sql = "INSERT INTO orders (total_price, email, note) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $email = sanitize($email);
        $note = sanitize($note);
        $stmt->bind_param("dss", $total, $email, $note);
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

        // Close database connection
        $conn->close();

        $success = true;
    }

?>

<?php include 'cart-html-header.php'; ?>

    <main>
        <section class="cart-container">
            <h2>Checkout</h2>

            <?php
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                // Insert order items into the order_items table
                foreach ($_SESSION['cart'] as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                }

                if (!empty($email)) echo '<p> &nbsp; <strong>Email:</strong> ' . $email . '</p>';
            

                echo '<table id="cart-table">';
                echo '<tr>';
                    echo '<th>Product</th>';
                    echo '<th>Price</th>';
                    echo '<th>Quantity</th>';
                    echo '<th>Total</th>';
                echo '</tr>';

                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                    echo '<tr>';
                        echo '<td width="100%">' . $item['product'] . '</td>';
                        echo '<td nowrap>' . number_format($item['price'], 2) . $currency . '</td>';
                        echo '<td width="200" align="right">' . $item['quantity'] . '</td>';
                        echo '<td nowrap>' . number_format($item['price'] * $item['quantity'], 2) . $currency . '</td>';
                    echo '</tr>';
                }

                echo '<tr>';
                    echo '<td colspan="3" align="right">Total</td>';
                    echo '<td>' . number_format($total, 2) . $currency . '</td>';
                echo '</tr>';
                echo '</table>';

                if (!$success) {
                    if (!empty($note)) echo '<p> &nbsp; <strong>Order details:</strong> ' . $note . '</p>'; ?>
                <center>
                    <form method="POST" action="checkout.php">
                        <input id="spam" name="spam" type="text" value="" placeholder="Anti-spam: <?=$antispam[rand(0,2)]?> is?" title="Enter the answer in digits. Like: '45'" />
                        <input type="hidden" id="confirm" name="confirm" value="1" />
                        <input type="hidden" id="email" name="email" value="<?=$email?>" />
                    </form>
                    <button id="checkoutBtn2">Confirm Order</button>
                </center>
                <? }

            } else {
                echo '<p>Your cart is empty.</p>';
            }


            if ($success) {
                // Clear
                unset($_SESSION['cart-email']);
                unset($_SESSION['cart-note']);
                unset($_SESSION['cart']);

                if (!empty($order_id))
                echo "<center><p>Order <strong>ID $order_id</strong> has been placed successfully. Check email for details &amp; receipt.<br><br><em>Thank you for shopping with us!</em></p></center>";                
            }            
            ?>        

            <br><center><a href="index.php">[Back to Shopping]</a></center>

        </section>
    </main>

    <script>
        $(document).ready(function() {          
          $('#checkoutBtn2').click(function() {
            var spam = $('#spam').val();
            if (spam == '') {
              alert('Answer the anti-spam question.');
            } else {
                //if (confirm('Proceed to payment?')) {
                  $('form').submit();
                //}
            }
          });
        });
    </script>

<?php include 'cart-html-footer.php'; ?>
