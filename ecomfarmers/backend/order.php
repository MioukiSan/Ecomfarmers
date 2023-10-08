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
                            <a href="sales.php" class="nav-link link-light">
                                <i class="bx bxs-cart me-2"></i>Sales & Transaction
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order.php" class="nav-link active" aria-current="page">
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
                function fetchDataWithJoin($conn) {
                    $query = "SELECT p.ID, b.Fullname, p.ProductName, p.Quantity, p.Total, p.Status
                    FROM products p
                    INNER JOIN billing b ON p.BillingID = b.ID";
                    
                    $result = $conn->query($query);
                    $data = array();
                    
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    
                    return $data;
                }

                $joinedData = fetchDataWithJoin($conn);

                $statusOptions = ['Ordered', 'In Delivery', 'Delivered', 'Canceled'];
            ?>

            <!-- Main Content -->
            <div class="col-12 col-xl-10">
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Order Management</h1>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($joinedData as $product) { ?>
                                        <tr>
                                            <td><?= $product['Fullname']; ?></td>
                                            <td><?= $product['ProductName']; ?></td>
                                            <td><?= $product['Quantity']; ?></td>
                                            <td><?= $product['Total']; ?></td>
                                            <td>
                                                <select class="form-select" name="status"
                                                    id="status<?= $product['ID']; ?>"
                                                    onchange="updateStatus(<?= $product['ID']; ?>, this)">
                                                    <?php foreach ($statusOptions as $statusOption) { ?>
                                                    <option value="<?= $statusOption; ?>"
                                                        <?= ($statusOption === $product['Status']) ? 'selected' : ''; ?>>
                                                        <?= $statusOption; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="order_delete.php?id=<?= $product['ID']; ?>"
                                                    class="btn btn-danger">Delete</a>
                                            </td>
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
    </div>

</body>
<!-- Include the necessary JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<!-- Include jQuery library if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateStatus(productId, statusSelect) {
    var selectedStatus = statusSelect.value;

    $.ajax({
        type: 'POST',
        url: 'order_update.php',
        data: {
            productId: productId,
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
</div>


</html>