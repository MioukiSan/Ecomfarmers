<?php
session_start();
$title = "checkout";
include 'components/header.php';
$ids = $_SESSION['id'];
$productId = $_GET['product_id'];

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
                 $sql = "SELECT p.*, pl.quantity AS quantityProd, pl.status AS product_status 
                     FROM preorder p 
                     LEFT JOIN product_list pl ON p.product_id = pl.id 
                     WHERE p.account_id = $ids AND p.id = $productId";
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
            <input type="hidden" name="products[<?= $productID ?>][name]" value="<?= $name ?>">
            <input type="hidden" name="products[<?= $productID ?>][quantity]" value="<?= $quantity?>">
            <input type="hidden" name="products[<?= $productID ?>][total]" value="<?= $total ?>">

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery (you can also use other Ajax libraries) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to toggle the accordion
        function toggleAccordion() {
            $('#collapseName').collapse('toggle');
        }

        // Bind the click event to the accordion header
        $('#accordionExample').click(function() {
            // Toggle the accordion
            toggleAccordion();
        });
    });
    </script>



    </html>