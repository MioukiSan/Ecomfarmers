<?php
    include '../connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $availID = $_POST['availID'];
        $newStatus = $_POST['status'];

        $updateQuery = "UPDATE availed_service SET avail_status = '$newStatus' WHERE avail_id = $availID";

        if ($conn->query($updateQuery) === TRUE) {
            $response = array('success' => true, 'message' => 'Status updated successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error updating status');
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>