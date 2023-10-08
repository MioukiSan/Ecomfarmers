<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['new_quantity'])) {
        $productID = $_POST['product_id'];
        $newQuantity = (int)$_POST['new_quantity'];

        // Validate and sanitize input if necessary

        // Fetch product details from your database or storage method
        $product = getProductDetails($productID);

        if ($product) {
            // Update the cart item quantity in the session
            if (!empty($_SESSION['cart'][$productID])) {
                $_SESSION['cart'][$productID]['quantity'] = $newQuantity;
            }

            // Calculate the updated product total and total price
            $productPrice = $product['price'];
            $productTotal = $productPrice * $newQuantity;

            $totalPrice = 0;
            foreach ($_SESSION['cart'] as $cartItem) {
                $totalPrice += $cartItem['price'] * $cartItem['quantity'];
            }

            // Prepare the response data
            $responseData = [
                'product_total' => number_format($productTotal, 2),
                'total_price' => number_format($totalPrice, 2)
            ];

            echo json_encode($responseData);
        } else {
            // Handle invalid product ID
            echo json_encode(['error' => 'Invalid product ID']);
        }
    } else {
        // Handle invalid input
        echo json_encode(['error' => 'Invalid input']);
    }
}

function getProductDetails($productID) {
    // Simulate fetching product details from a database
    $products = [
        1 => [
            'id' => 1,
            'title' => 'Sample Product 1',
            'price' => 10.99,
            // Other product details
        ],
        2 => [
            'id' => 2,
            'title' => 'Sample Product 2',
            'price' => 19.99,
            // Other product details
        ],
        // Add more products as needed
    ];

    return isset($products[$productID]) ? $products[$productID] : null;
}
?>