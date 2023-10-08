<?php
    session_start(); // Start the session
    include 'connect.php';

    if (isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address']; // Added line
        $location = $_POST['location'];
        $paymentMethod = $_POST['payment-method'];
        $status = 'Ordered';

        // Check if contact number starts with "09" and has 11 digits
        if (preg_match('/^09\d{9}$/', $contact) !== 1) {
            echo "<script>alert('Contact number should start with 09 and have exactly 11 digits. Please provide a valid Philippine contact number.'); window.location.href='checkout.php'; </script>";
        } else {
            // Generate a unique Order_ID
            $orderID = uniqid();

            // Insert billing information into the database
            $insertBillingQuery = "INSERT INTO billing (Fullname, Email, Contact, address, Location, PaymentMethod, Order_ID)
                                    VALUES ('$fullname', '$email', '$contact', '$address', '$location', '$paymentMethod', '$orderID')";

            if ($conn->query($insertBillingQuery) === TRUE) {
                $billingID = $conn->insert_id;

                if (isset($_POST['products'])) {
                    foreach ($_POST['products'] as $productID => $productData) {
                        $productname = $productData['name'];
                        $quantity = $productData['quantity'];
                        $total = $productData['total'];

                        $insertProductQuery = "INSERT INTO products (BillingID, ProductName, Quantity, Total, Status)
                                            VALUES ('$billingID', '$productname', '$quantity', '$total', '$status')";
                        $conn->query($insertProductQuery);
                    }
                }

                // Clear the shopping cart after successful insertion
                $_SESSION['cart'] = array();

                echo "<script>alert('Billing information and product details inserted successfully! Your Order ID is $orderID.'); window.location.href='index.php'</script>";
            } else {
                echo "Error: " . $insertBillingQuery;
            }
        }
    }
?>