<?php
    session_start();
    $title = "dashboard";
    include '../components/backend_header.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-12 col-xl-2 navside">
                <div
                    class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 bg">
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
                            <a href="sales.php" class="nav-link link-light">
                                <i class="bx bxs-cart me-2"></i>Sales Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order.php" class="nav-link active" aria-current="page">
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
                function fetchDataWithJoin($conn, $statusFilter = null) {
                    $query = "SELECT BillingID, MIN(Status) AS Status
                            FROM products
                            GROUP BY BillingID";

                    if ($statusFilter !== null && $statusFilter !== 'NULL') {
                        $query .= " HAVING Status = '$statusFilter'";
                    }

                    $result = $conn->query($query);
                    $data = array();

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    return $data;
                }

                $joinedData = fetchDataWithJoin($conn);

                $statusOptions = ['Ordered', 'In Delivery', 'Delivered', 'Canceled'];

                $statusFilter = 0;
                if (isset($_POST['filterStatus'])) {
                    $statusFilter = $_POST['filterStatus'];
                    $joinedData = fetchDataWithJoin($conn, $statusFilter);
                }
            ?>

            <!-- Main Content -->
            <div class="col-12 col-xl-10">
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Order Management</h1>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <!-- filter dropdown button -->
                            <form action="" method="POST">
                                <div class="mb-3" style="width: 15%;">
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
                                            <th>Customer Name</th>
                                            <th>Order Details</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($joinedData as $product) { 
                                            $BillingID = $product['BillingID'];
                                            $queryBillingdata = "SELECT * FROM billing WHERE id = $BillingID";
                                            $BillingdataRes = mysqli_query($conn, $queryBillingdata);

                                            foreach($BillingdataRes as $BillingData){ 
                                        ?>
                                            <tr>
                                                <td><?= $BillingData['Fullname']; ?></td>
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
                                                <td>
                                                    <select class="form-select" name="status" id="status<?= $BillingID; ?>" onchange="updateStatus(<?= $BillingID; ?>, this)">
                                                        <?php foreach ($statusOptions as $statusOption) { ?>
                                                            <option value="<?= $statusOption; ?>" <?= ($statusOption === $product['Status']) ? 'selected' : ''; ?>>
                                                                <?= $statusOption; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a href="order_delete.php?id=<?= $BillingID; ?>" class="btn btn-danger">Delete</a>
                                                </td>
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

</body>

<!-- Include the necessary JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<!-- Include jQuery library if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateStatus(BillingID, statusSelect) {
    var selectedStatus = statusSelect.value;

    $.ajax({
        type: 'POST',
        url: 'order_update.php',
        data: {
            BillingID: BillingID,
            status: selectedStatus
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
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