<?php
    function generateSalesTransactionCode($conn) {
        $sql = "SELECT Order_ID FROM billing WHERE Order_ID LIKE ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        $pattern = 'SAUD' . '%';
        $stmt->bind_param("s", $pattern);
        
        $stmt->execute();
        $result = $stmt->get_result();
      
        if (!$result || $result->num_rows === 0) {
            $unique_part = 1;
        } else {
            $lastTransactionCode = $result->fetch_object();
      
            $lastUniquePart = substr($lastTransactionCode->sales_transaction_code, strlen('JGS') + strlen(date('dmY')) + 1);
      
            // Increment the transaction counter by 1.
            $unique_part = $lastUniquePart + 1;
        }
      
        // Generate the sales transaction code.
        $code = 'SAUD' . '-000' . $unique_part;
      
        // Return the sales transaction code.
        return $code;
    }
?>