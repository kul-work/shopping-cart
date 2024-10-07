<?php 
    session_start();
    include_once 'db.php';
    
    $note = isset($_SESSION['cart-note']) ? $_SESSION['cart-note'] : '';
    $email = isset($_SESSION['cart-email']) ? $_SESSION['cart-email'] : '';
?>    

<?php include 'cart-html-header.php'; ?>

    <main>
        <section class="cart-container">
            <h2>Shopping Cart</h2>

            <?php
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                echo '<form id="cartForm" method="post" action="checkout.php">';
                echo '<table id="cart-table">';
                echo '<tr>';
                    echo '<th>Name</th>';
                    echo '<th>Price</th>';
                    echo '<th>Quantity</th>';
                    echo '<th>Total</th>';
                    echo '<th>Action</th>';
                echo '</tr>';

                $total = 0;
                foreach ($_SESSION['cart'] as $key => $item) {
                    $item_total = $item['price'] * $item['quantity'];
                    $total += $item_total;

                    echo '<tr>';
                        echo '<td width="50%">' . $item['product'] . '</td>';
                        echo '<td width="25%" nowrap>' . number_format($item['price'], 2) . $currency . '</td>';
                        echo '<td width="25%"><input type="number" class="qty" data-key="' . $key . '" value="' . $item['quantity'] . '" min="1"></td>';
                        echo '<td align="right" nowrap>' . number_format($item_total, 2) . $currency . '</td>';
                        echo '<td align="left"><a class="removeItemBtn" data-key="' . $key . '" href="#">[x]</a></td>';
                    echo '</tr>';
                }

                echo '<tr>';
                    echo '<td colspan="3" align="right">Total</td>';
                    echo '<td nowrap>' . number_format($total, 2) . $currency . '</td>';
                echo '<td></td>';
                echo '</tr>';
                echo '</table>';
                
                echo '<center>';
                echo '<input id="email" name="email" class="cart-email" type="email" placeholder="Your e-mail" value="'.$email.'" />';
                echo '<textarea id="note" name="note" class="cart-note" placeholder="Add delivery details: phone, address etc.">' . $note . '</textarea>';
                echo '</center>';
                echo '</form>';

                // Show "Empty Cart" button if cart is not empty
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {                
                    echo '<center><button id="emptyCartBtn" type="button">Empty Cart</button>';
                    echo '&nbsp; &nbsp; &nbsp;';
                }

                echo '<button type="submit" id="checkoutBtn">Checkout</button>';
                echo '</center>';

                
            } else {
                echo '<p>Your cart is empty.</p>';
            }    


            echo '<br /><br /><center><a href="index.php">[Continue Shopping]</a></center>';        
            ?>

        </section>
    </main>

    <script>
        $(document).ready(function(){
            $('.qty').change(function(){
                var key = $(this).data('key');
                var quantity = $(this).val();

                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {mode: 'update', key: key, quantity: quantity},
                    success: function(response) {
                        // Update the total and item total without reloading the page
                        var total = parseFloat(response); //less errors getting it from the cart.php
                        var itemPrice = parseFloat($('#cartForm').find('input[type=number][data-key='+key+']').closest('tr').find('td').eq(1).text().replace('<?=$currency?>', ''));
                        var newTotal = (total).toFixed(2);
                        var newTotalItem = (itemPrice * quantity).toFixed(2);
                        console.log(itemPrice);
                        $('#cartForm').find('td').eq(-2).text(newTotal+'<?=$currency?>');
                        $('#cartForm').find('input[type=number][data-key='+key+']').closest('td').next('td').text(newTotalItem + '<?=$currency?>');
                    }
                });
            });

            $('#note').on('input', function() {
                // Update the note in session variable
                var note = $(this).val();
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {mode: 'note', note: note},
                    success: function(response) {
                        // Optional: You can add success message or other handling here
                    }
                });
            });

            $('#emptyCartBtn').click(function() {
                $.ajax({
                    url: 'cart.php?mode=empty',
                    method: 'GET',
                    success: function(response) {
                        // Remove the note from session variable
                        $('#note').val('');
                        // Reload the page or any other handling
                        location.reload();
                    }
                });
            });

            $('.removeItemBtn').click(function() {
                var key = $(this).data('key');
                $.ajax({
                    url: 'cart.php?mode=remove&id='+key,
                    method: 'GET',
                    success: function(response) {
                        // Reload the page or any other handling
                        location.reload();
                    }
                });
            });

            $("#email").on("change", function() { 
                var email = $(this).val();
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {mode: 'email', email: email},
                    success: function(response) {
                        console.log(email)
                        // Optional: You can add success message or other handling here
                    }
                });
            });

            $('#checkoutBtn').on('click', function() {
                var email = $('#email').val();
                if (email == '') {
                    alert('Email is empty.');
                } else if (!validateEmail(email)) {
                    alert('Please enter a valid email address.');
                } else {
                    $('form').submit();
                }
            });

            function validateEmail(email) {
                // Simple regular expression to check for valid email format
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return emailPattern.test(email);
            }
            
        });
    </script>

<?php include 'cart-html-footer.php'; ?>
