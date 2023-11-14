<?php
    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['product_id'])) {
        $productId = $_GET['product_id'];

        $checkQuery = "SELECT id FROM preorder WHERE id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $deleteQuery = "DELETE FROM preorder WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $productId);

            if ($stmt->execute()) {
                // Redirect to preorder.php with a success message
                header("Location: preorder.php?success=1");
                exit();
            } else {
                // Redirect to preorder.php with an error message
                header("Location: preorder.php?error=1");
                exit();
            }
        } else {
            // Redirect to preorder.php with a not found message
            header("Location: preorder.php?not_found=1");
            exit();
        }
    } else {
        // Redirect to preorder.php with an invalid request message
        header("Location: preorder.php?invalid_request=1");
        exit();
    }
?>
