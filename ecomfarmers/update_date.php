<?php
include 'connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $availId = isset($_POST['avail_id']) ? intval($_POST['avail_id']) : 0;
    $newDate = isset($_POST['date']) ? $_POST['date'] : '';

    // Perform the update only if both avail_id and date are valid
    if ($availId > 0 && $newDate !== '') {
        // Update the date in the availed_service table
        $updateSql = "UPDATE availed_service SET date = ? WHERE avail_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $newDate, $availId);

        if ($stmt->execute()) {
            // Date updated successfully
            echo "<script>alert('Date updated successfully.');</script>";
        } else {
            // Error updating date
            echo "<script>alert('Error updating date.');</script>";
        }

        $stmt->close();
    } else {
        // Invalid input
        echo "<script>alert('Invalid input.');</script>";
    }
}

// Redirect to the previous page or any other appropriate location
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
