<?php
    include '../connect.php';

    if (isset($_POST['submit'])) {
        $productId = $_POST['id'];
        $title = $_POST['title'];
        $price = $_POST['price'];
        $categories = $_POST['categories'];
        $harvest = $_POST['harvest'];

        // Check if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $targetDir = '../img/';
            $targetFile = $targetDir . basename($image);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Valid image formats
            $allowedImageFormats = ["jpg", "jpeg", "png", "gif"];

            // File size check
            $maxFileSize = 500000; // 500KB

            if (!in_array($imageFileType, $allowedImageFormats)) {
                showError("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            } elseif ($_FILES["image"]["size"] > $maxFileSize) {
                showError("Sorry, your file is too large.");
            } elseif (!getimagesize($_FILES["image"]["tmp_name"])) {
                showError("File is not a valid image.");
            } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                showError("Sorry, there was an error uploading your file.");
            } else {
                updateProductWithImage($productId, $title, $price, $categories, $image);
            }
        } else {
            updateProductWithoutImage($productId, $title, $price, $categories, $harvest);
        }
    }

    function showError($message) {
        echo "<script>alert('$message'); window.location.href='product.php';</script>";
    }

    function updateProductWithImage($productId, $title, $price, $categories, $image) {
        global $conn;
        $sql = "UPDATE product_list 
                SET 
                    image = '$image', 
                    title = '$title', 
                    price = $price, 
                    categories = '$categories'
                WHERE id = $productId";
        performUpdate($sql);
    }

    function updateProductWithoutImage($productId, $title, $price, $categories, $harvest) {
        global $conn;
        $sql = "UPDATE product_list 
                SET 
                    title = '$title', 
                    price = $price, 
                    categories = '$categories',
                    harvest = '$harvest'
                WHERE id = $productId";
        performUpdate($sql);
    }

    function performUpdate($sql) {
        global $conn;
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product updated successfully.'); window.location.href='product.php';</script>";
        } else {
            echo "<script>alert('Product not updated successfully.'); window.location.href='product.php';</script>";
        }
    }
?>