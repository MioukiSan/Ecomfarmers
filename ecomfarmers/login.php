<?php
session_start();
$title = "login";
include 'components/header.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please enter a username and password.');</script>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM account WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $row['usertype'];

            if ($row['usertype'] == 'Admin') {
                header("location: backend/dashboard.php");
                exit;
            } elseif ($row['usertype'] == 'User') {
                header("location: index.php");
                exit;
            }
        } else {
            echo "<script>alert('Incorrect username or password.');</script>";
        }
    }
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['usertype'] == 'Admin') {
        header("location: backend/dashboard.php");
        exit;
    } elseif ($_SESSION['usertype'] == 'User') {
        header("location: index.php");
        exit;
    }
}
?>

<body>
    <div class="container pt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-8 col-lg-6">
                <img src="img/logo.png" alt="Logo" class="w-25 d-flex m-auto mb-3">
                <form action="#" method="POST">
                    <h1 class="text-center mb-4 text-uppercase fw-bolder">Login</h1>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                            required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                            required>
                        <label for="password">Password</label>
                    </div>
                    <button type="submit" class="btn btn-primary d-block w-100" name="submit"
                        id="submit">Submit</button>
                    <p class="mt-3">You don't have an account? <a href="register.php">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>