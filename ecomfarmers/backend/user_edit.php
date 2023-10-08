<?php
    include '../connect.php';
    
    if(isset($_POST['submit'])) {
        $id = $_GET['id'];
        $fullname = $conn->real_escape_string($_POST['fullname']);
        $username = $conn->real_escape_string($_POST['username']);
        $usertype = $conn->real_escape_string($_POST['usertype']);
        
        // Get the existing password hash from the database
        $password_query = "SELECT password FROM account WHERE id = $id";
        $password_result = $conn->query($password_query);
        
        if ($password_result) {
            $row = $password_result->fetch_assoc();
            $existing_password_hash = $row['password'];
            
            $sql = "UPDATE account SET fullname = '$fullname', username = '$username', usertype = '$usertype', password = '$existing_password_hash' WHERE id = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Edit User successfully'); window.location.href='user.php';</script>";
            } else {
                echo "<script>alert('Edit User not successfully'); window.location.href='user.php';</script>";
            }
        } else {
            echo "<script>alert('Edit User not successfully'); window.location.href='user.php';</script>";
        }
        
        $password_result->free();
    }
?>