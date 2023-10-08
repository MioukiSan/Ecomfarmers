<?php
    session_start();
    $title = "style";
    include 'components/header.php';

    if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] !== 'User') {
        header("location: login.php");
        exit;
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
                    <li><a href="index.php" target="_self">Home</a></li>
                    <li><a href="#sidebar" target="_self">Product</a></li>
                    <li><a href="services.php" target="_self" class="active">Other Services</a></li>
                    <li><a href="about.php" target="_self">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <div class="hero pt-2" id="hero">
        <div class="text-container">
            <h1 class="fw-bolder m-0">"SAUD": A Web-based marketplace of Banco Santiago de Libon</h1>
            <p>Connecting customers to your brand <span>Buy-Sale-Deal-Local</span></p>
        </div>
        <img src="img/cover.png" alt="Hero" class="img-fluid">
    </div>

    <?php
        $categories = ["Carpenter", "Plumber"];

        $selectedCategories = [];
        if (isset($_GET['category']) && is_array($_GET['category'])) {
            $selectedCategories = $_GET['category'];
        }

        $whereClause = "";
        if (!empty($selectedCategories)) {
            $categoryConditions = [];
            foreach ($selectedCategories as $selectedCategory) {
                $escapedCategory = mysqli_real_escape_string($conn, $selectedCategory);
                $categoryConditions[] = "categories = '$escapedCategory'";
            }
            $whereClause = "WHERE " . implode(" OR ", $categoryConditions);
        }

        $sql = "SELECT id, image, title, description, price, quantity, categories, timestamp FROM services $whereClause";
        $result = $conn->query($sql);

        $services = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
    ?>

    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-lg-3 sidebar" id="sidebar">
                <h2 class="fw-bolder">Search</h2>
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search services">
                <h2 class="fw-bolder">Categories</h2>
                <?php foreach ($categories as $category) { ?>
                <div class="category">
                    <label>
                        <input type="checkbox" class="category-checkbox" name="category[]"
                            value="<?= htmlspecialchars($category); ?>"
                            <?= in_array($category, $selectedCategories) ? 'checked' : ''; ?>>
                        <?= htmlspecialchars($category); ?>
                    </label>
                </div>
                <?php } ?>
            </div>

            <div class="col-lg-9">
                <div class="row" id="productContainer">
                    <?php foreach ($services as $service) { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="img/<?= htmlspecialchars($service['image']); ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($service['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title fw-bolder"><?= htmlspecialchars($service['title']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($service['description']); ?></p>
                                <p class="card-text price">₱ <?= number_format($service['price'], 2); ?></p>
                                <form action="services_cart.php" method="get">
                                    <input type="hidden" name="services_id" value="<?= $service['id'] ?>">
                                    <button type="submit" class="btn btn-primary border-0" name="submit">Avail Service
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p class="m-0">&copy; 2023 E-Commerce Farmers. All rights reserved.</p>
    </footer>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.category-checkbox').on('change', function() {
        const selectedCategories = [];
        $('.category-checkbox:checked').each(function() {
            selectedCategories.push($(this).val());
        });

        $('#productContainer').fadeOut(150, function() {
            $.ajax({
                url: 'product_get.php',
                method: 'GET',
                data: {
                    category: selectedCategories
                },
                success: function(data) {
                    $('#productContainer').html(data).fadeIn(150);
                }
            });
        });
    });
});

$(document).ready(function() {
    $("#searchInput").on("input", function() {
        var searchText = $(this).val().toLowerCase();
        $(".card").each(function() {
            var title = $(this).find(".card-title").text().toLowerCase();
            if (title.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>

</html>