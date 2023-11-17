<?php
    session_start();
    $title = "style";
    include 'components/header.php';

    // if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] !== 'User') {
    //     header("location: login.php");
    //     exit;
    // }

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
                    <li><a href="#sidebar" target="_self" class="active">Products</a></li>
                    <li><a href="services.php" target="_self">Services</a></li>
                    
                    <li><a href="about.php" target="_self">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <!-- <div class="hero pt-2" id="hero">
        <div class="text-container">
            <h1 class="fw-bolder m-0">"SAUD": A Web-based Marketplace of Banco Santiago de Libon</h1>
            <p>Connecting customers to your brand <span>Buy-Sale-Deal-Local</span></p>
        </div>
        <img src="img/cover.png" alt="Hero" class="img-fluid">
    </div> -->

    <?php
        $getCategoriesQuery = "SELECT cat_name FROM categories";
        $getCategoriesStmt = $conn->prepare($getCategoriesQuery);
        $getCategoriesStmt->execute();
        $categoriesResult = $getCategoriesStmt->get_result();
        
        // Check if there are categories in the database
        if ($categoriesResult->num_rows > 0) {
            // Fetch categories into an associative array
            $categories = [];
            while ($row = $categoriesResult->fetch_assoc()) {
                $categories[] = $row['cat_name'];
            }
        } else {
            // Default categories if none found in the database
            $categories = ["Vegetables", "Fish", "Meats", "Rice", "Fruit"];
        }

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
            $whereClause = "WHERE " . implode(" OR ", $categoryConditions) . "AND status != 'Sold' AND quantity != '0'";
        }
        $preOrderCondition = "";
        if (isset($_GET['preorder'])) {
            $preOrderCondition = "status = 'Pre Order'";
        }    
        $sql = "SELECT id, image, title, description, price, quantity, unit, harvest, status FROM product_list $whereClause";
        $result = $conn->query($sql);

        $products = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
    ?>

    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-lg-3 sidebar" id="sidebar">
                <h2 class="fw-bolder">Search</h2>
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search products">
                <h2 class="fw-bolder">Categories</h2>
                <?php foreach ($categories as $category) { ?>
                <div class="category">
                    <label>
                        <input type="checkbox" class="category-checkbox" name="category[]"
                            value="<?php echo $category; ?>"
                            <?php if (in_array($category, $selectedCategories)) echo 'checked'; ?>>
                        <?php echo $category; ?>
                    </label>
                </div>
                <?php } ?>
            </div>

            <div class="col-lg-9">
                <div class="row" id="productContainer">
                    <?php foreach ($products as $product) { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="img/<?php echo $product['image']; ?>" class="card-img-top"
                                alt="<?php echo $product['title']; ?>">
                            <div class="card-body">
                                <!-- <h5 class="card-title fw-bolder"><?php echo $product['title']; ?></h5> -->
                                <div class="d-sm-flex">
                                    <div class="col-md-6">
                                        <h5 class="card-title fw-bolder"><?php echo $product['title']; ?></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text price">₱ <?php echo $product['price'] .'/'. $product['unit']; ?></p>
                                    </div>
                                </div>
                                <p class="card-text"><?php echo $product['description']; ?></p>
                                <!-- <p class="card-text price">₱ <?php echo $product['price'] .'/'. $product['unit']; ?></p> -->
                                <!-- Form for 'On Sale' products -->
                                <form action="cart.php" method="get">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <div class="text-center">
                                        <?php if($product['status'] === 'On Sale') { ?>
                                            <button type="submit" class="btn btn-primary border-0" name="submit">Add to Cart</button>
                                        <?php } ?>
                                    </div>
                                </form>

                                <!-- Form for 'Pre Order' products -->
                                <form action="preorder_add.php" method="post">
                                    <?php if($product['status'] === 'Pre Order') { ?>
                                        <input type="hidden" name="ProdID" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="name" value="<?= $product['title'] ?>">
                                        <input type="hidden" name="price" value="<?= $product['price'] ?>">
                                        <div class="text-center">
                                            <p><?= 'harvest Date:' . $product['harvest'] ?></p>
                                            <button class="btn btn-primary border-0" name="preorder" type="submit">Pre Order</button>
                                        </div>
                                    <?php } ?>
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