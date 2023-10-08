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
                    <li><a href="index.php#sidebar" target="_self">Product</a></li>
                    <li><a href="services.php" target="_self">Other Services</a></li>
                    <li><a href="about.php" target="_self">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <?php
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_GET['services_id'])) {
            $servicesID = intval($_GET['services_id']);

            global $conn;

            $sql = "SELECT * FROM services WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $servicesID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $services = $result->fetch_assoc();

                $cartItem = [
                    'id' => $services['id'],
                    'name' => $services['title'],
                    'price' => $services['price'],
                    'quantity' => 1
                ];

                if (isset($_SESSION['cart'][$servicesID])) {
                    $_SESSION['cart'][$servicesID]['quantity']++;
                } else {
                    $_SESSION['cart'][$servicesID] = $cartItem;
                }
            }
        }
    ?>

    <div class="container mt-5">
        <h1 class="text-center fw-bolder mb-5">Shopping Cart</h1>
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
                    $totalPrice = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $servicesID => $cartItem) {
                            $name = $cartItem['name'];
                            $price = $cartItem['price'];
                            $quantity = $cartItem['quantity'];
                            $total = $price * $quantity;
                            $totalPrice += $total;
                    ?>
                <tr>
                    <td><?= $name ?></td>
                    <td>₱ <?= number_format($price, 2) ?></td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control text-center quantity-input"
                                value="<?= $quantity ?>" data-product-id="<?= $servicesID ?>">
                        </div>
                    </td>
                    <td class="product-total">₱ <?= number_format($total, 2) ?></td>
                    <td><a href="cart_remove.php?product_id=<?= $servicesID ?>"><i class="bx bx-trash"></i></a></td>
                </tr>
                <?php
                }
            } else {
                echo '<tr><td colspan="5">Your cart is empty.</td></tr>';
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
            <a href="services_checkout.php" class="btn btn-primary">Checkout</a>
            <a href="index.php" class="btn btn-success">Continue to Shopping</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        initializeQuantities();
        startPolling();

        $(".quantity-input").on("input", function() {
            var servicesID = $(this).data("services-id");
            var newQuantity = $(this).val();
            updateCartQuantity(servicesID, newQuantity);
        });

        $(".update-link").on("click", function(event) {
            event.preventDefault();
            var servicesID = $(this).data("services-id");
            var newQuantity = prompt("Enter new quantity:",
                ""); // Use a prompt or another UI method to get the new quantity
            if (newQuantity !== null) {
                updateCartQuantity(servicesID, newQuantity);
            }
        });

        function initializeQuantities() {
            $(".quantity-input").each(function() {
                var servicesID = $(this).data("services-id");
                var initialQuantity = $(this).val();
                setInitialQuantity(servicesID, initialQuantity);
            });
        }

        function startPolling() {
            setInterval(function() {
                updateCartQuantities();
            }, 500); // Polling interval in milliseconds (e.g., 5 seconds)
        }

        function updateCartQuantities() {
            $(".quantity-input").each(function() {
                var servicesID = $(this).data("services-id");
                var initialQuantity = getInitialQuantity(servicesID);
                var currentQuantity = $(this).val();

                if (currentQuantity !== initialQuantity) {
                    updateCartQuantity(servicesID, currentQuantity);
                }
            });
        }

        function getInitialQuantity(servicesID) {
            return localStorage.getItem(`services_${servicesID}_quantity`);
        }

        function setInitialQuantity(servicesID, initialQuantity) {
            localStorage.setItem(`services_${servicesID}_quantity`, initialQuantity);
        }

        function updateCartQuantity(servicesID, newQuantity) {
            $.ajax({
                method: "POST",
                url: "cart_quantity.php",
                data: {
                    product_id: servicesID,
                    new_quantity: newQuantity
                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    updateProductTotal(servicesID, responseData.product_total);
                    updateTotalPrice(responseData.total_price);
                    updateQuantityInput(servicesID, newQuantity);
                    setInitialQuantity(servicesID, newQuantity);
                }
            });
        }

        function updateProductTotal(servicesID, newTotal) {
            $(`tr[data-product-id="${servicesID}"] .product-total`).text(`$${newTotal.toFixed(2)}`);
        }

        function updateTotalPrice(newTotal) {
            $(".total-price").text(`$${newTotal.toFixed(2)}`);
        }

        function updateQuantityInput(servicesID, newQuantity) {
            $(`input[data-product-id="${servicesID}"]`).val(newQuantity);
        }
    });
    </script>

</body>

</html>