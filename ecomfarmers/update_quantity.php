<?php
session_start();
include 'connect.php';

if (isset($_POST['quantity']) && isset($_POST['product_id']) && isset($_POST['acc_id'])) {
    $quantity = $_POST['quantity'];
    $productId = $_POST['product_id'];
    $accId = $_POST['acc_id'];

    // Perform the update in the database
    $sqlUpdate = "UPDATE preorder SET quantity_pre = ? WHERE account_id = ? AND product_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("iii", $quantity, $accId, $productId);

    if ($stmtUpdate->execute()) {
        // Return a success message or any data you need
        echo "Quantity updated successfully!";
    } else {
        // Return an error message or handle the error accordingly
        echo "Error updating quantity: " . $stmtUpdate->error;
    }

    // Close the statement and connection
    $stmtUpdate->close();
    $conn->close();
} else {
    // Handle invalid or missing parameters
    echo "Invalid parameters!";
}
?>
