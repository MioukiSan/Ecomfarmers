<?php
    session_start();
    $title = "profile";
    include 'components/header.php';

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

<body>
    <div class="wrapper w-100">
        <nav class="d-flex justify-content-between align-items-center flex-wrap">
            <a href="index.php"><img src="img/logo.png" class="logo" alt="Company Logo" /></a>
            <input type="checkbox" name="" id="toggle">
            <label for="toggle"><i class='bx bx-menu'></i></label>
            <div class="menu">
                <ul class="d-flex align-items-center list-unstyled gap-5 m-0">
                    <li><a href="index.php" target="_self">Home</a></li>
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
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="fw-bolder">Availed Service History</h2>
                <div class="dropdown">
                    <button class="btn btn-success" onclick="toggleDropdown()">History</button>
                    <div class="dropdown-menu" id="historyDropdown" style="display: none;">
                        <a class="dropdown-item" href="order_history.php">Order History</a>
                        <a class="dropdown-item" href="#">Service Availed History</a>
                        <a class="dropdown-item" href="#">Pre Order</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Service Name</th>
                                <th>Service Price Range</th>
                                <th>Status</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlGET = "SELECT a.avail_id, a.service_id, a.date, a.avail_status, s.title, s.service_price_range, a.account_id, d.fullname 
                                    FROM availed_service a 
                                    LEFT JOIN services s ON s.id = a.service_id
                                    LEFT JOIN account d ON d.id = a.account_id
                                    WHERE a.avail_status != 'cart'";
                                $joinedData = mysqli_query($conn, $sqlGET);

                                foreach ($joinedData as $product) { 
                            ?>
                                <tr>
                                    <td><?= $product['fullname']; ?></td>
                                    <td><?= $product['title']; ?></td>
                                    <td><?= $product['service_price_range']; ?></td>
                                    <td><?= $product['avail_status'] ?></td>
                                    <!-- <td>
                                        <a href="service_remove_cart1.php?avail_id=<?= $service['avail_id'] ?>" class="btn btn-danger">Delete</a>
                                    </td> -->
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("historyDropdown");
        dropdown.style.display = (dropdown.style.display === 'none') ? 'block' : 'none';
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function (event) {
        if (!event.target.matches('.btn-success')) {
            var dropdown = document.getElementById("historyDropdown");
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        }
    }
</script>