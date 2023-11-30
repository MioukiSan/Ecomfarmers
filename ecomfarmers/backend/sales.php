<?php
    session_start();
    $title = "dashboard";
    include '../components/backend_header.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
        <div class="col-12 col-xl-2 navside">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 bg">
                    <div class="d-flex">
                        <img src="../img/logo.png" class="logo me-2" alt="pos">
                        <a href="dashboard.php"
                            class="d-flex align-items-center mb-md-0 me-md-auto text-white text-decoration-none">
                            <span class="fw-bold"><?php echo "Welcome, ", $_SESSION['username']; ?></span>
                        </a>
                        <button class="btn text-center menubut" id="menu-sm-screen"><i class='bx bx-menu bx-md' style='color:#ffffff'  ></i></button>
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
                            <a href="service.php" class="nav-link link-light">
                                <i class="bx bx-bulb me-2"></i>Service Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sales.php"  class="nav-link active" aria-current="page">
                                <i class="bx bxs-cart me-2"></i>Sales Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order.php" class="nav-link link-light">
                                <i class='bx bxs-bookmark me-2'></i>Order Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="service_avail.php" class="nav-link link-light">
                                <i class='bx bxs-hard-hat me-2'></i>Service Avail Management
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown drop">
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
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php

                // Fetch sales data (products) along with billing information using BillingID
            $query = "SELECT p.`ID`, p.`BillingID`, p.`ProductName`, p.`Quantity`, p.`Total`, b.`Fullname`, b.`Email`, b.`Contact`, b.address, b.`Address`, b.`PaymentMethod`, b.`Timestamp` FROM `products` p LEFT JOIN `billing` b ON p.`BillingID` = b.`ID`";
            $salesData = mysqli_query($conn, $query);

            ?>

            <!-- Main Content -->
            <div class="col-12 col-xl-10">
                
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Sales Report</h1>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-1 float-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selecttoprint"><i class='bx bxs-printer'></i>PRINT</button>
                            <div class="modal fade" id="selecttoprint" aria-hidden="true" aria-labelledby="bodySelect" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body text-center">
                                            <button class="btn btn-warning" data-bs-target="#specificdate" data-bs-toggle="modal">Specific Date</button>
                                            <button class="btn btn-success" data-bs-target="#inbetween" data-bs-toggle="modal">Between Date</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="specificdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Specific" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="Specific">GENERATE SALES REPORT</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="generate_sales_report.php" method="POST" target="_target">
                                            <div class="input-group">
                                                <span class="input-group-text">This Date</span>
                                                <input type="date" name="this_date" class="form-control" required>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="specific" class="btn btn-warning">GENERATE</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="inbetween" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="between" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="between">GENERATE SALES REPORT</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="generate_sales_report.php" method="POST" target="_target">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Between</span>
                                            <input type="date" name="start_date" class="form-control" required>
                                            <span class="input-group-text"> and </span>
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="between" class="btn btn-success">GENERATE</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-light">
                                <thead>
                                    <th>Billing ID</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th>Payment Method</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($salesData as $sale) {
                                        $billingID = $sale['BillingID'];
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $billingID; ?>
                                            </td>
                                            <td><?= $sale['Fullname']; ?></td>
                                            <td><?= $sale['address']; ?></td>
                                            <td><?= $sale['PaymentMethod']; ?></td>
                                            <td><?= $sale['ProductName']; ?></td>
                                            <td><?= $sale['Quantity']; ?></td>
                                            <td><?= $sale['Total']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- Include the necessary JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
  const menuButton = document.getElementById('menu-sm-screen');
  const navSide = document.querySelector('.navside');

  menuButton.addEventListener('click', function () {
    navSide.classList.toggle('active');
  });
});

</script>
</html>