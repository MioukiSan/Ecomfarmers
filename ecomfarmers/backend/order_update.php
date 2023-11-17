<?php
    include '../connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $BillingID = $_POST['BillingID'];
        $newStatus = $_POST['status'];

        // Update all products with the same BillingID
        $updateQuery = "UPDATE products SET Status = '$newStatus' WHERE BillingID = $BillingID";

        if ($conn->query($updateQuery) === TRUE) {
            $response = array('success' => true, 'message' => 'Status updated successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error updating status');
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>
