<?php
    session_start();
    $title = "dashboard";
    include '../components/backend_header.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-12 col-xl-2">
                <div
                    class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 bg">
                    <div class="d-flex">
                        <img src="../img/logo.png" class="logo me-2" alt="pos">
                        <a href="index.php"
                            class="d-flex align-items-center mb-md-0 me-md-auto text-white text-decoration-none">
                            <span class="fw-bold"><?php echo "Welcome, ", $_SESSION['username']; ?></span>
                        </a>
                    </div>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto w-100">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link link-light">
                                <i class="bx bxs-dashboard me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user.php" class="nav-link link-light">
                                <i class='bx bxs-user me-2'></i>User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="product.php" class="nav-link link-light">
                                <i class="bx bxl-product-hunt me-2"></i>Product Management
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="sales.php" class="nav-link active" aria-current="page">
                                <i class="bx bxs-cart me-2"></i>Sales & Transaction
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order.php" class="nav-link link-light">
                                <i class='bx bxs-bookmark me-2'></i>Order Management
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle ms-2"
                            id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../img/logo.png" alt="pos" width="32" height="32" class="rounded-circle me-2">
                            <strong><?php echo $_SESSION['username']; ?></strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser2">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
                function fetchData($conn, $query) {
                    $stmt = $conn->prepare($query);
                    
                    // Add error handling here
                    
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    return $data;
                }

                    // Fetch sales data (products) along with billing information using BillingID
                    $query = "SELECT p.`ID`, p.`BillingID`, p.`ProductName`, p.`Quantity`, p.`Total`, b.`Fullname`, b.`Email`, b.`Contact`, b.address, b.`Address`, b.`PaymentMethod`, b.`Timestamp` FROM `products` p LEFT JOIN `billing` b ON p.`BillingID` = b.`ID`";
                    $salesData = fetchData($conn, $query);

                    // Group sales data by BillingID
                    $groupedSalesData = [];
                    foreach ($salesData as $sale) {
                    $billingID = $sale['BillingID'];
                    $groupedSalesData[$billingID][] = $sale;
                }
            ?>

            <!-- Main Content -->
            <div class="col-12 col-xl-10">
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Sales & Transaction</h1>
                    <hr>
                    <?php
                        $salesPerPage = 2;
                        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                        $startIndex = ($currentPage - 1) * $salesPerPage;
                        $endIndex = $startIndex + $salesPerPage;
                    ?>
                    <div class="row">
                        <?php
                            $counter = 0;
                            foreach ($groupedSalesData as $billingID => $sales) {
                                if ($counter >= $startIndex && $counter < $endIndex) {
                                    $firstSale = $sales[0];
                        ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Billing ID: <?= $billingID; ?></h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($sales as $sale) : ?>
                                                <tr>
                                                    <td><?= $sale['ProductName']; ?></td>
                                                    <td><?= $sale['Quantity']; ?></td>
                                                    <td><?= $sale['Total']; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Customer Name</td>
                                                    <td><?= $firstSale['Fullname']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><?= $firstSale['Email']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Mobile</td>
                                                    <td><?= $firstSale['Contact']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>address</td>
                                                    <td><?= $firstSale['address']; ?></td> <!-- Added address -->
                                                </tr>
                                                <tr>
                                                    <td>Address</td>
                                                    <td><?= $firstSale['Address']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Method</td>
                                                    <td><?= $firstSale['PaymentMethod']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Timestamp</td>
                                                    <td><?= $firstSale['Timestamp']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                                $counter++;
                                if ($counter >= $endIndex) {
                                    break;
                                }
                            }
                        ?>
                    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php
                                $totalPages = ceil(count($groupedSalesData) / $salesPerPage); // Calculate total number of pages

                                if ($currentPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                                }

                                for ($page = 1; $page <= $totalPages; $page++) {
                                    if ($page == $currentPage) {
                                        echo '<li class="page-item active"><span class="page-link">' . $page . '</span></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                                    }
                                }

                                if ($currentPage < $totalPages) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                                }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
            </nav>
        </div>
    </div>

</body>
<!-- Include the necessary JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

</html>