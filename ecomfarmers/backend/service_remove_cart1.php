<?php
session_start();
include '../connect.php';

if (isset($_GET['avail_id'])) {
    $availID = $_GET['avail_id'];

    // Remove from availed_service table
    $deleteSql = "DELETE FROM availed_service WHERE avail_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $availID);

    if ($stmt->execute()) {
        // Product removed successfully
        echo "<script>alert('Product removed successfully.');</script>";
    } else {
        // Error removing product
        echo "<script>alert('Error removing product.');</script>";
    }

    $stmt->close();
    
    // Redirect to the cart or any other appropriate location
    header("Location: service_avail.php");
    exit();
}
?>
