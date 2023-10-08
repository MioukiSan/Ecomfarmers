<?php
    include '../connect.php';
    $userId = $_GET['id'];

    // Fetch billing ID associated with the user
    $billingQuery = "SELECT BillingID FROM products WHERE ID = $userId";
    $billingResult = $conn->query($billingQuery);
    if ($billingResult->num_rows > 0) {
        $billingData = $billingResult->fetch_assoc();
        $billingId = $billingData['BillingID'];

        // Delete product from products table first
        $deleteProductQuery = "DELETE FROM products WHERE ID = $userId";
        if ($conn->query($deleteProductQuery) === TRUE) {
            // Delete billing from billing table afterwards
            $deleteBillingQuery = "DELETE FROM billing WHERE ID = $billingId";
            if ($conn->query($deleteBillingQuery) === TRUE) {
                echo "<script>alert('User and associated data deleted successfully.'); window.location.href='order.php';</script>";
            } else {
                echo "<script>alert('Failed to delete associated billing data.'); window.location.href='order.php';</script>";
            }
        } else {
            echo "<script>alert('Failed to delete user.'); window.location.href='order.php';</script>";
        }
    } else {
        echo "<script>alert('No associated billing data found.'); window.location.href='order.php';</script>";
    }
?>