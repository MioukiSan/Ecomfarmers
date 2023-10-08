<?php
    session_start();

    if (isset($_GET['product_id'])) {
        $productID = $_GET['product_id'];

        unset($_SESSION['cart'][$productID]);

        header("Location: cart.php");
        exit();
    }
?>