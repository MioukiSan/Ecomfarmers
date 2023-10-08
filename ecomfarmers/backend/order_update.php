<?php
    include '../connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['productId'];
        $newStatus = $_POST['status'];

        $updateQuery = "UPDATE products SET Status = '$newStatus' WHERE ID = $productId";

        if ($conn->query($updateQuery) === TRUE) {
            $response = array('success' => true, 'message' => 'Status updated successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error updating status');
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>