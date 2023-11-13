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
<html>
<body class="justify-content-center">
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
    <div class="row bg-white shadow border m-5">
        <h1 class="text-center" style="color: green; margin-top: .5em;"><b>ABOUT US</b></h1>
        <div class="col-md-3 col-sm-3">
            <img src="./img/logo (3).png" style="width: 20em; height: 15em;">
        </div>
        <div class="col-md-9 col-sm-9">
            <div class="text-center p-4" style="margin-top: 3em;">
            <h2>SAUD: Marketplace of Banco Santiago de Libon</h2>
            <h5>At SAUD, we are dedicated to transforming the way you experience agriculture. 
                As a unique marketplace backed by Banco Santiago de Libon, 
                we take pride in bridging the gap between farmers and consumers, bringing the bounty of the land directly to your fingertips.</h5>
            </div>
        </div>
    </div>
    <div class="row bg-white shadow border m-5">
        <h1 class="text-center" style="color: green; margin-top: .5em;"><b>WHAT WE OFFERS</b></h1>
        <div class="col-md-6 col-sm-6">
            <div class="text-center p-4" style="margin-top: 3em;">
            <img src="./img/weoffer1.png" style="width: 20em; height:auto;">
            <h2>SAUD: Marketplace of Banco Santiago de Libon</h2>
            <h5>At SAUD, we are dedicated to transforming the way you experience agriculture. 
                As a unique marketplace backed by Banco Santiago de Libon, 
                we take pride in bridging the gap between farmers and consumers, bringing the bounty of the land directly to your fingertips.</h5>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="text-center p-4" style="margin-top: 3em;">
            <img src="./img/weoffer2.png" style="width: 20em; height:auto;">
            <h2>SAUD: Marketplace of Banco Santiago de Libon</h2>
            <h5>At SAUD, we are dedicated to transforming the way you experience agriculture. 
                As a unique marketplace backed by Banco Santiago de Libon, 
                we take pride in bridging the gap between farmers and consumers, bringing the bounty of the land directly to your fingertips.</h5>
            </div>
        </div>
    </div>
<div class="mt-1 text-center bg-white shadow border">
    <h3 class="text-center p-3" style="color: green;"><b>PARTNER BRANCHES</b></h3>
<iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3882.924847880235!2d123.48734237463685!3d13.29263500775947!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1a0f0a3491a4b%3A0x5a781ad5b7d314c5!2sBanco%20Santiago%20De%20Libon!5e0!3m2!1sen!2sph!4v1699756597104!5m2!1sen!2sph"
        width="400" height="350" style="border: 12px;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d902.6028705168185!2d123.4384077586262!3d13.299473965953926!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1a0278cc1a33f%3A0x1391dcc70b1ba109!2sRURAL%20BANK%20OF.SANTIAGO%20DE%20LIBON!5e0!3m2!1sen!2sph!4v1699756822489!5m2!1sen!2sph"
        width="400" height="350" style="border: 12px;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1360.182949646855!2d123.50049404168132!3d13.256544964455323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a10a73f5aabf1d%3A0x6b948f28514451c6!2sWestern%20Union%20-%20Banco%20Santiago%20De%20Libon%20-%20Oas!5e0!3m2!1sen!2sph!4v1699756923646!5m2!1sen!2sph"
        width="400" height="350" style="border: 12px;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
</body>
<footer class="mt-5">
    <p class="m-0">&copy; 2023 E-Commerce Farmers. All rights reserved.</p>
</footer>
</html>
