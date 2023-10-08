<?php
    session_start();
    $title = "register";
    include 'components/header.php';

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        header("location: index.php");
        exit;
    }

    if(isset($_POST['submit'])) {
        $fullname = $_POST['full_name'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $location = $_POST['location'];

        // Validate contact number using regular expression
        $contact_pattern = '/^09\d{9}$/'; // Philippine contact number pattern
        if (!preg_match($contact_pattern, $contact)) {
            echo "<script>alert('Invalid contact number. Please enter a valid Philippine contact number.'); window.location.href='register.php'</script>";
            exit; // Stop execution if validation fails
        }

        // Set the timezone to Philippine time
        date_default_timezone_set('Asia/Manila');
        $timestamp = date('Y-m-d H:i:s');

        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM `account` WHERE `username` = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $existing_count = $check_result->fetch_row()[0];

        if ($existing_count > 0) {
            echo "<script>alert('Username already exists.'); window.location.href='register.php'</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO `account` (`fullname`, `username`, `password`, `email`, `contact`, `address`, `location`, `timestamp`, `usertype`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'User')");
            $stmt->bind_param("ssssssss", $fullname, $username, $password, $email, $contact, $address, $location, $timestamp);
        
            if ($stmt->execute()) {
                echo "<script>alert('Record added successfully.'); window.location.href='login.php'</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
?>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <img src="img/logo.png" alt="Logo" class="w-25 d-flex m-auto mb-3">
                <form action="#" method="POST">
                    <h1 class="text-center mb-4 text-uppercase fw-bolder">Register</h1>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="form-floating col-6 mb-3 me-2">
                            <input type="text" class="form-control" name="full_name" id="full_name"
                                placeholder="Full Name" required>
                            <label for="full_name">Full Name</label>
                        </div>
                        <div class="form-floating col-6 mb-3">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                                required>
                            <label for="username">Username</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="form-floating col-6 mb-3 me-2">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating col-6 mb-3">
                            <input type="email" class="form-control" name="email" id="floatingInput"
                                placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" name="contact" id="contact" placeholder="Contact"
                            required>
                        <label for="contact">Contact</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="address" id="address" placeholder="address"
                            required>
                        <label for="address">Address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="location" id="location" required>
                            <option value="Polangui">Polangui</option>
                            <option value="Libon">Libon</option>
                            <option value="Oas">Oas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary d-block w-100" name="submit"
                        id="submit">Submit</button>
                    <p class="mt-3">You have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>