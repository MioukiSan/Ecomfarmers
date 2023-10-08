<?php
session_start();
$title = "checkout";
include 'components/header.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart_empty.php');
    exit();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
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

<body?>
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

    <div class="container mt-3">
        <h1 class="text-center fw-bolder mb-5">Checkout</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalPrice = 0;
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
                    <td><?= $quantity ?></td>
                    <td>₱ <?= number_format($total, 2) ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">Total:</td>
                    <td>₱ <?= number_format($totalPrice, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <h2 class="text-center mt-5 fw-bolder mb-4">Billing Information</h2>
        <form action="checkout_process.php" method="POST" id="billing-form">
            <!-- Hidden input fields to store product information -->
            <?php foreach ($_SESSION['cart'] as $productID => $cartItem) { ?>
            <input type="hidden" name="products[<?= $productID ?>][name]" value="<?= $cartItem['name'] ?>">
            <input type="hidden" name="products[<?= $productID ?>][quantity]" value="<?= $cartItem['quantity'] ?>">
            <input type="hidden" name="products[<?= $productID ?>][total]"
                value="<?= $cartItem['price'] * $cartItem['quantity'] ?>">
            <?php } ?>

            <div class="accordion mb-3" id="accordionExample">
                <!-- Billing Information -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingName">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseName" aria-expanded="true" aria-controls="collapseName">
                            Billing Information Details
                        </button>
                    </h2>
                    <div id="collapseName" class="accordion-collapse collapse show" aria-labelledby="headingName"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <?php
                                    // Check if the user is logged in (adjust this condition accordingly)
                                    if (isset($_SESSION['username'])) {
                                        global $conn;

                                        $username = $_SESSION['username'];

                                        // Fetch billing information for the logged-in user
                                        $sql = "SELECT `id`, `fullname`, `email`, `contact`, `address`, `location` FROM `account` WHERE `username` = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("s", $username);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            foreach ($row as $fieldName => $fieldValue) {
                                                echo '<div class="col-md-6">';
                                                echo '<label for="' . $fieldName . '" class="form-label">' . ucfirst($fieldName) . '</label>';
                                                echo '<input type="text" id="' . $fieldName . '" name="' . $fieldName . '" class="form-control" value="' . $fieldValue . '" required>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="col-12">';
                                            echo '<p>No billing information found for this user.</p>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<div class="col-12">';
                                        echo '<p>User not logged in.</p>';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="payment-method" class="form-label">Payment Method:</label>
                <select id="payment-method" name="payment-method" class="form-select" required>
                    <option value="" selected disabled>Select payment method</option>
                    <option value="Pick-Up">Pick-Up</option>
                    <option value="Gcash">Gcash</option>
                    <option value="COD">Cash On Delivery</option>
                </select>
                <small class="text-danger" id="payment-method-error"></small>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Place Order</button>
        </form>
    </div>

    <footer>
        <p class="m-0">&copy; 2023 E-Commerce Farmers. All rights reserved.</p>
    </footer>

    </body>
    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </html>