<?php
    include '../connect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
        $productId = $_POST['service_id'];

        $checkQuery = "SELECT id FROM product_list WHERE id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $deleteQuery = "DELETE FROM services WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $productId);

            if ($stmt->execute()) {
                echo "<script>alert('Service deleted successfully.'); window.location.href='service.php';</script>";
            } else {
                echo "<script>alert('Service deletion failed.'); window.location.href='service.php';</script>";
            }
        } else {
            echo "<script>alert('Service not found.'); window.location.href='service.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid request.'); window.location.href='service.php';</script>";
    }
?>