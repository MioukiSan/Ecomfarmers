<?php
    session_start();
    $title = "dashboard";
    include '../components/backend_header.php';

    if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] !== 'Admin') {
        header("location: ../login.php");
        exit;
    }
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
                            <a href="dashboard.php" class="nav-link active" aria-current="page">
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
// Define functions to fetch data
function getCount($conn, $table)
{
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Fetch data using functions
$accountCount = getCount($conn, 'account');
$productCount = getCount($conn, 'products');
$productListCount = getCount($conn, 'product_list');

// Define card information array
$cardInfo = array(
    'All Account' => $accountCount,
    'All Sales' => $productCount,
    'All Product List' => $productListCount,
);
?>

<!-- Main Content -->
            <div class="col-12 col-xl-10">
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Dashboard</h1>
                    <hr>
                    <div class="row">
                        <?php
                        foreach ($cardInfo as $title => $count) {
                        ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $title; ?></h5>
                                        <p class="card-text">
                                            <?php
                                            echo 'You have ' . $count . ' ';
                                            if ($title === 'Staff' || $title === 'Patients') {
                                                echo $title;
                                            } else {
                                                if ($title === 'All Sales') {
                                                    echo 'total sales as of today.';
                                                } elseif ($title === 'All Product List') {
                                                    echo 'total active products.';
                                                } else {
                                                    echo 'active user accounts.';
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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