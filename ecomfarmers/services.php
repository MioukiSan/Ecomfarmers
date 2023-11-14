<?php
    session_start();
    $title = "style";
    include 'components/header.php';

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
        <a href="services_cart.php" class="btn btn-primary text-decoration-none border-0" id="cart-button">
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
                    <li><a href="index.php#sidebar" target="_self">Products</a></li>
                    <li><a href="services.php" target="_self" class="active">Services</a></li>
                    <li><a href="about.php" target="_self">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-lg-12">
                <!-- search bar -->
                <div class="row" id="productContainer">
                    <?php
                        $sql = "SELECT * FROM services";
                        $sqlres = mysqli_query($conn, $sql);

                        foreach($sqlres as $service){
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="img/<?= htmlspecialchars($service['image']); ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($service['title']); ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <h5 class="card-title fw-bolder"><?= htmlspecialchars($service['title']); ?></h5>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="card-text price">₱ <?= $service['service_price_range'] ?></p>
                                    </div>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($service['description']); ?></p>
                                <form action="services_cart.php" method="get">
                                    <div class="text-center">
                                        <input type="hidden" name="services_id" value="<?= $service['id'] ?>">
                                        <button type="submit" class="btn btn-primary border-0" name="submit">Avail Service
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

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
    <!-- <footer>
        <p class="m-0">&copy; 2023 E-Commerce Farmers. All rights reserved.</p>
    </footer> -->
</html>