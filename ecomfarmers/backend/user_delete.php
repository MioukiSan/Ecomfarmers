<?php
    include '../connect.php';
    $userId = $_GET['id'];
    
    $deleteQuery = "DELETE FROM `account` WHERE `id` = $userId";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('User deleted successfully.'); window.location.href='user.php';</script>";
    } else {
        echo "<script>alert('User not deleted successfully.'); window.location.href='user.php';</script>";
    }
?>