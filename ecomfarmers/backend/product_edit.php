<?php
include '../connect.php';

if (isset($_POST['submit'])) {
    $productId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $categories = $_POST['categories'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $targetDir = '../img/';
        $targetFile = $targetDir . basename($image);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate uploaded image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not a valid image.'); window.location.href='product.php';</script>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "<script>alert('Sorry, your file is too large.'); window.location.href='product.php';</script>";
            $uploadOk = 0;
        }

        // Allow only specific image file formats
        $allowedImageFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedImageFormats)) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='product.php';</script>";
            $uploadOk = 0;
        }

        // Upload file and update database
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $sql = "UPDATE product_list 
                        SET 
                            image = '$image', 
                            title = '$title', 
                            description = '$description', 
                            price = $price, 
                            quantity = $quantity, 
                            categories = '$categories'
                        WHERE id = $productId";

                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Product updated successfully.'); window.location.href='product.php';</script>";
                } else {
                    echo "<script>alert('Product not updated successfully.'); window.location.href='product.php';</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='product.php';</script>";
            }
        }
    } else {
        $sql = "UPDATE product_list 
                SET 
                    title = '$title', 
                    description = '$description', 
                    price = $price, 
                    quantity = $quantity, 
                    categories = '$categories',
                    harvest = '$harvest'
                WHERE id = $productId";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product updated successfully.'); window.location.href='product.php';</script>";
        } else {
            echo "<script>alert('Product not updated successfully.'); window.location.href='product.php';</script>";
        }
    }
}
?>