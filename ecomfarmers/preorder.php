<?php
    session_start();
    $title = "cart";
    include 'components/header.php';

    function getUsername() {
        if(!isset($_SESSION['username'])) {
          header('Location: login.php');
          exit();
        }
    }
    $ids = $_SESSION['id'];
    function getLogin() {
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            echo '<li><a href="profile.php"><i class="bx bx-user"></i></a></li>';
            echo '<li><a href="logout.php" class="btn btn-primary">Logout</a></li>';
        } else {
            echo '<li><a href="login.php" class="btn btn-primary">Login</a></li>';
        }
    }
?>

<body>
    <div class="position-bottom-right">
        <a href="cart.php" class="btn btn-primary text-decoration-none border-0" id="cart-button">
            <i class='bx bxs-cart'></i>
        </a>
    </div>

    <div class="wrapper w-100">
        <nav class="d-flex justify-content-between align-items-center flex-wrap">
            <a href="index.php"><img src="img/logo.png" class="logo" /></a>
            <input type="checkbox" name="" id="toggle">
            <label for="toggle"><i class='bx bx-menu'></i></label>
            <div class="menu">
                <ul class="d-flex align-items-center list-unstyled gap-5 m-0">
                    <li><a href="index.php" target="_self" class="active">Home</a></li>
                    <li><a href="index.php#sidebar" target="_self">Products</a></li>
                    <li><a href="services.php" target="_self">Services</a></li>
                    <li><a href="about.php" target="_self">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>
    <div class="container mt-5">
    <h1 class="text-center fw-bolder mb-5">Pre Orders</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.*, pl.quantity AS quantityProd, pl.status AS product_status 
                FROM preorder p 
                LEFT JOIN product_list pl ON p.product_id = pl.id 
                WHERE p.account_id = $ids";
            $result = mysqli_query($conn, $sql);
            $totalPrice = 0;
            foreach ($result as $preorder) {
                $name = $preorder['title'];
                $accID = $preorder['account_id'];
                $quantity = $preorder['quantity_pre'];
                $productID = $preorder['product_id'];
                $price = $preorder['price'];
                $total = $price * $quantity;
                $totalPrice += $total;
                $preorderID = $preorder['id'];
            ?>
                <tr>
                    <td><?= $name ?></td>
                    <td>₱ <?= number_format($price, 2) ?></td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control text-center quantity-input"
                                value="<?= $quantity ?>" data-product-id="<?= $productID ?>"
                                data-acc-id="<?= $accID ?>" max="<?= $preorder['quantityProd'] ?>"
                                onchange="updateQuantity(this)">
                        </div>
                    </td>       
                    <td class="product-total" id="total-<?= $productID ?>">₱ <?= number_format($total, 2) ?></td>
                    <td>
                        <?php if ($preorder['product_status'] == 'Pre Order') { ?>
                            <button class="btn btn-primary" disabled>Checkout</button>
                        <?php } else { ?>
                            <a href="preorder_checkout.php?product_id=<?= $preorderID ?>" class="btn btn-primary">Checkout</a>
                        <?php } ?>
                        <a href="cart_remove.php?product_id=<?= $preorderID ?>"><i class="bx bx-trash"></i></a>
                    </td>
                </tr>
            <?php
            }

            // If there are no pre-orders, display a message
            if (empty($result)) {
                echo '<tr><td colspan="5">Your pre-order list is empty.</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end">Total:</td>
                <td>₱ <?= number_format($totalPrice, 2) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="text-end">
        <a href="index.php" class="btn btn-success">Continue Shopping</a>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
        function updateQuantity(input) {
            var quantity = $(input).val();
            var productId = $(input).data('product-id');
            var accId = $(input).data('acc-id');

            // AJAX request
            $.ajax({
                type: 'POST',
                url: 'update_quantity.php', 
                data: {
                    quantity: quantity,
                    product_id: productId,
                    acc_id: accId
                },
                success: function (response) {
                    // Handle the response (if needed)
                    console.log(response);
                },
                error: function (error) {
                    // Handle the error (if needed)
                    console.error(error);
                }
            });
        }
    </script>
</html>