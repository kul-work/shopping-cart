<?php 
    session_start();
    include_once 'db.php';

    // Initialize cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Calculate total price of items in the cart
    $total_price = 0;
    $total_items = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
        $total_items += $item['quantity'];
    }
?>

<?php include 'cart-html-header.php'; ?>

    <main>
        <section class="cart-container">
            <h2>Product Catalog</h2>

            <?php            
            // Output cart content
            if ($total_items > 0) {
                $pl = ($total_items > 1) ? 's' : '';
                echo '<div class="basket-cnt"><div id="basket"><a href="view_cart.php" title="View Cart">&#128722; <span id="basket_items">' . $total_items . '</span> item'.$pl.', TOTAL = <span id="basket_total">' . number_format($total_price, 2) . $currency . '</span></a></div></div>';
            } else {
                echo '<div class="basket-cnt"><div id="basket">&#128722; is empty</div></div>';
            }


            $sql = "SELECT id, name, description, price FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table id="cart-table">';
                echo '<tr>';
                    echo '<th>Name</th>';
                    echo '<th>Price</th>';
                    echo '<th>Description</th>';
                    echo '<th>Qty</th>';
                    echo '<th>Action</th>';
                echo '</tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td nowrap>' . $row['price'] . $currency . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td><input class="qty" id="quantity_'.$row['id'].'" type="number" value="1" min="1"></td>';
                        echo '<td><button class="addtocartBtn" data-product-id="'.$row['id'].'" data-product-name="'.$row['name'].'" data-product-price="'.$row['price'].'">Add to Cart</button></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "No products found.";
            }

            echo '<br /><br /><center><a href="view_cart.php">[View Cart]</a></center>';

            // Close database connection
            $conn->close();
            ?>

        </section>
    </main>

    <!-- AJAX script with jQuery to add product to cart without refreshing the page -->
    <script>
        $(document).ready(function(){
            $('.addtocartBtn').click(function(){
                var product_id = $(this).data('product-id');
                var product_name = $(this).data('product-name');
                var product_price = $(this).data('product-price');
                var product_quantity = $('#quantity_'+product_id).val();
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {product_id: product_id, product: product_name, price: product_price, quantity: product_quantity},
                    success: function(response) {
                        // Update cart content on the page
                        if ($('#basket').text().toLowerCase().includes('empty')) {
                            var total_items = 1;
                            var total_price = parseFloat(product_price);
                        } else {
                            var total_items = parseInt($('#basket_items').text()) + parseInt(product_quantity);
                            var total_price = parseFloat($('#basket_total').text()) + parseInt(product_quantity) * parseFloat(product_price);
                        }
                        pl = 's'; if (total_items == 1) pl = '';
                        $('#basket').html('<a href="view_cart.php" title="View Cart">&#128722; <span id="basket_items">' + total_items + '</span> item'+pl+', TOTAL = <span id="basket_total">' + parseFloat(total_price).toFixed(2) + '<?=$currency?>' + '</span></a>');

                        // Some 'animation'
                        $('#basket').css('border-color', '#de5b9c');
                        $('#basket').css('border-width', '2px');
                        setTimeout(function() {
                            $('#basket').css('border-color', '#999');}, 
                            250);
                        setTimeout(function() {                            
                            $('#basket').css('border-width', '1px');}, 
                            450);

                    }
                });
            });
        });
    </script>

<?php include 'cart-html-footer.php'; ?>
