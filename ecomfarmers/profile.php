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
    $username = $_SESSION['username'];

    // Prepare the SQL statement to retrieve user information
    $sql = "SELECT `id`, `fullname`, `email`, `contact`, `address`, `location`, `username`, `password`, `timestamp`, `usertype` FROM `account` WHERE `username` = ?";
    $stmt = $conn->prepare($sql);

    // Bind the username to the prepared statement
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Now, fetch the user's order history and associated products for all users
        $orderHistorySql = "SELECT b.Order_ID, b.Fullname, p.ProductName, p.Quantity, p.Total, p.Status 
        FROM billing b 
        JOIN products p ON b.ID = p.BillingID";

        $orderResult = $conn->query($orderHistorySql);
        } else {
            echo "User not found.";
            exit;
        }
?>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="fw-bolder">User Profile</h2>
                <div class="dropdown">
                    <button class="btn btn-success" onclick="toggleDropdown()">History</button>
                    <div class="dropdown-menu" id="historyDropdown" style="display: none;">
                        <a class="dropdown-item" href="order_history.php">Order History</a>
                        <a class="dropdown-item" href="service_avail_history.php">Service Availed History</a>
                        <a class="dropdown-item" href="preorder.php">Pre Order</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
            $fields = [
                'Full Name' => 'fullname',
                'Username' => 'username',
                'Email' => 'email',
                'Contact' => 'contact',
                'Address' => 'address',
                'Location' => 'location'
            ];

            foreach ($fields as $label => $fieldId):
            ?>
                <div class="mb-3">
                    <label for="<?= $fieldId ?>" class="form-label"><?= $label ?>:</label>
                    <input type="text" class="form-control" id="<?= $fieldId ?>"
                        value="<?= htmlspecialchars($row[$fieldId]) ?>" readonly>
                </div>
                <?php endforeach; ?>
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