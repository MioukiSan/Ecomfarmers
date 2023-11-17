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
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="fw-bolder">Order History</h2>
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
                function fetchDataWithJoin($conn, $statusFilter = null) {
                    // Use prepared statement to prevent SQL injection
                    $query = "SELECT p.BillingID, MIN(p.Status) AS Status, b.Fullname
                            FROM products p
                            LEFT JOIN billing b ON p.BillingID = b.ID
                            WHERE b.fullname = ?";

                    if ($statusFilter !== null && $statusFilter !== 'NULL') {
                        $query .= " AND p.Status = ?";
                    }

                    $query .= " GROUP BY p.BillingID";

                    $stmt = $conn->prepare($query);

                    if ($statusFilter !== null && $statusFilter !== 'NULL') {
                        $stmt->bind_param("ss", $_SESSION['fullname'], $statusFilter);
                    } else {
                        $stmt->bind_param("s", $_SESSION['fullname']);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    $data = array();

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    $stmt->close();

                    return $data;
                }

                    $joinedData = fetchDataWithJoin($conn);

                    $statusOptions = ['Ordered', 'In Delivery', 'Delivered', 'Canceled'];

                    $statusFilter = 0;
                    if (isset($_POST['filterStatus'])) {
                        // Validate and sanitize user input before using it in the query
                        $statusFilter = $_POST['filterStatus'];
                        $joinedData = fetchDataWithJoin($conn, $statusFilter);
                    }
                ?>

                <div class="col-12 col-xl-12">
                        <div class="row">
                            <div class="col">
                                <!-- filter dropdown button -->
                                <form action="" method="POST">
                                    <div class="mb-3" style="width: 10%;">
                                        <select class="form-select" name="filterStatus" id="filterStatus" onchange="this.form.submit()">
                                            <option value="NULL">All</option>
                                            <?php foreach ($statusOptions as $statusOption) { ?>
                                                <option value="<?= $statusOption; ?>" <?= ($statusOption === $statusFilter) ? 'selected' : ''; ?>>
                                                    <?= $statusOption; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Order ID</th>
                                                <th>Order Details</th>
                                                <th>Payment Method</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($joinedData as $product) { 
                                                $BillingID = $product['BillingID'];
                                                $queryBillingdata = "SELECT * FROM billing WHERE id = $BillingID";
                                                $BillingdataRes = mysqli_query($conn, $queryBillingdata);

                                                foreach($BillingdataRes as $BillingData){ 
                                            ?>
                                                <tr class="text-center">
                                                    <td><?= $BillingData['Order_ID']; ?></td>
                                                    <td>
                                                        <div class="table responsive">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Name</th>
                                                                        <th>Quantity</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $queryOrderData = "SELECT * FROM products WHERE BillingID = $BillingID";
                                                                        $OrderDataRes = mysqli_query($conn, $queryOrderData);
                                                                        foreach($OrderDataRes as $OrderData){
                                                                    ?>
                                                                        <tr>
                                                                            <td><?= $OrderData['ProductName']; ?></td>
                                                                            <td><?= $OrderData['Quantity']; ?></td>
                                                                            <td><?= $OrderData['Total']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                    <td><?= $BillingData['PaymentMethod'] ?></td>
                                                    <td><?= $OrderData['Status'] ?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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