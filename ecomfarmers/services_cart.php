<?php
    session_start();
    $title = "cart";
    include 'components/header.php';
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit();
    }
    $idACCOUNT = $_SESSION['id'];
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

    if (isset($_POST['checkout'])) {
        $id = $_POST['ID'];
    
        $getCheck = "SELECT avail_id FROM availed_service WHERE account_id = $id";
        $getCheckres = mysqli_query($conn, $getCheck);
    
        while ($row = mysqli_fetch_assoc($getCheckres)) {
            $avail_id = $row['avail_id'];
    
            if (!empty($avail_id)) {
                $updateSql = "UPDATE availed_service SET avail_status = 'Ordered' WHERE avail_id = $avail_id";
                $updateResult = mysqli_query($conn, $updateSql);
    
                if ($updateResult) {
                    echo '<script>alert("Checkout successful!");</script>';
                } else {
                    echo "Error updating avail_status: " . mysqli_error($conn);
                }
            }
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

    <?php

        if (isset($_GET['services_id'])) {
            $servicesID = intval($_GET['services_id']);

            global $conn;

            // Fetch service details
            $sql = "SELECT * FROM services WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $servicesID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $services = $result->fetch_assoc();

                // Check if the service is already availed by the user
                $checkSql = "SELECT * FROM availed_service WHERE service_id = '{$services['id']}' AND account_id = {$_SESSION['id']} AND avail_status != 'Completed'";
                $checkResult = $conn->query($checkSql);

                if ($checkResult->num_rows == 0) {
                    $service_avail_ref = generateReference();

                    // Insert into availed_service table
                    $insertSql = "INSERT INTO availed_service (service_avail_ref, service_id, avail_status, account_id) VALUES ('$service_avail_ref', '{$services['id']}', 'cart', '{$_SESSION['id']}')";
                    
                    if ($conn->query($insertSql)) {
                        // Service added to availed_service table successfully
                    } else {
                        // Error inserting into availed_service table
                    }
                } else {
                    // Service is already availed, handle accordingly
                }
            }
        }

        // Function to generate a reference (replace this with your own logic)
        function generateReference() {
            // Your logic to generate a unique reference
            return "REF_" . uniqid();
        }

    ?>
    <div class="container mt-5">
        <h1 class="text-center fw-bolder mb-5">Service Cart</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price Range</th>
                    <th>Date of Service</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlGET = "SELECT a.avail_id, a.service_id, a.date, a.avail_status, s.title, s.service_price_range FROM availed_service a 
                        LEFT JOIN services s ON s.id = a.service_id
                        WHERE a.avail_status = 'cart'";
                $sqlGETres = mysqli_query($conn, $sqlGET);

                if ($sqlGETres && mysqli_num_rows($sqlGETres) > 0) {
                    foreach ($sqlGETres as $service) {
                ?>
                        <tr>
                            <td><?= $service['title'] ?></td>
                            <td>₱ <?= $service['service_price_range'] ?></td>
                            <td>
                                <div class="input-group">
                                    <form action="update_date.php" method="POST" class="updateDateForm">
                                        <input type="hidden" name="avail_id" value="<?php echo $service['avail_id'] ?>">
                                        <input type="date" class="form-control text-center" value="<?php echo $service['date'] ?>" name="date" onchange="updateDate(this)">
                                    </form>
                                </div>
                            </td>
                            <td><a href="service_remove_cart.php?avail_id=<?= $service['avail_id'] ?>"><i class="bx bx-trash"></i></a></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="4">Your cart is empty.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="text-end">
            <form action="" method="POST">
                <input type="hidden" name="ID" value="<?php echo $idACCOUNT ?>">
                <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
                <a href="index.php" class="btn btn-success">Continue Shopping</a>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

<script>
    function updateDate(input) {
        // Assuming the form has an ID of "updateDateForm"
        var form = input.closest('.updateDateForm');
        // Trigger the form submission
        form.submit();
    }
</script>

</html>