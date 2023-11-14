<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['preorder'])) {
    $ids = $_SESSION['id'];
    $prodID = $_POST['ProdID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = 1;

    // Check if the same product_id and account_id combination already exists
    $sqlCheck = "SELECT quantity_pre FROM preorder WHERE account_id = ? AND product_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $ids, $prodID);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existingQty);

    if ($stmtCheck->fetch()) {
        // Close the result set of the first query
        $stmtCheck->close();

        // If the record exists, update the quantity
        $newQty = $existingQty + $qty;

        $sqlUpdate = "UPDATE preorder SET quantity_pre = ? WHERE account_id = ? AND product_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("iii", $newQty, $ids, $prodID);

        if ($stmtUpdate->execute()) {
            // Close the statements and connection
            $stmtUpdate->close();
            $conn->close();

            // Redirect to preorder.php
            header("Location: preorder.php");
            exit();
        } else {
            // Error - you can add an error message or handle the error accordingly
            echo "Error updating quantity: " . $stmtUpdate->error;
            
            // Close the statements and connection
            $stmtUpdate->close();
            $conn->close();
        }
    } else {
        // If the record does not exist, insert a new record
        $stmtCheck->close();

        // Using prepared statements to prevent SQL injection
        $sqlInsert = "INSERT INTO preorder (account_id, title, product_id, quantity_pre, price) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("issid", $ids, $name, $prodID, $qty, $price);

        if ($stmtInsert->execute()) {
            // Close the statements and connection
            $stmtInsert->close();
            $conn->close();

            // Redirect to preorder.php
            header("Location: preorder.php");
            exit();
        } else {
            // Error - you can add an error message or handle the error accordingly
            echo "Error inserting new record: " . $stmtInsert->error;
            
            // Close the statements and connection
            $stmtInsert->close();
            $conn->close();
        }
    }
}
?>
