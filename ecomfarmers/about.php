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
                    
                    <li><a href="about.php" target="_self" class="active">About</a></li>
                    <?php
                        getLogin();
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <iframe class="mt-3"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124231.17602771065!2d123.41268049407334!3d13.336321992480489!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1a059e3dc8ec7%3A0x4a7340fccb2ae057!2sPolangui%2C%20Albay!5e0!3m2!1sen!2sph!4v1695038584561!5m2!1sen!2sph"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d248609.63635283103!2d123.13205974715447!3d13.192286159914078!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a10a73b472f673%3A0x78251e0e1212c250!2sOas%2C%20Albay!5e0!3m2!1sen!2sph!4v1695038613811!5m2!1sen!2sph"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <iframe class="mb-5"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124275.73229855111!2d123.28865519267131!3d13.249358632821563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a175b7df7e2021%3A0xb0990c6e61aff2e2!2sLibon%2C%20Albay!5e0!3m2!1sen!2sph!4v1695038629272!5m2!1sen!2sph"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>

    <footer>
        <p class="m-0">&copy; 2023 E-Commerce Farmers. All rights reserved.</p>
    </footer>

</body>