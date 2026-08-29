<?php
$local = "localhost";
$user = "root";
$pass = "";
$db = "tourism";

$conn = mysqli_connect($local, $user, $pass, $db);

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "Login successful";
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign in</title>
    <link rel="stylesheet" href="signin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-6 col-md-6 col-sm-12 left-section p-5 d-flex flex-column justify-content-between text-white">
                <h3>safrny</h3>
                <h1 style="justify-content: center;
<h1 class="text-center>"the world a book ,and those who do not travel ready only one page"</h1>   
         </div>
            <div class="col-lg-6 col-md-6 col-sm-12 bg-white p-5 d-flex flex-column justify-content-center align-items-center">
                <h2><i>welcome back</i></h2>
                <h6>sign in to manage your booking and wishlist</h6>
                <div class="form w-100" style="max-width: 400px;">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword5" class="form-label">Password</label>
                            <input type="password" name="password" id="inputPassword5" class="form-control" required>
                        </div>
                        <div> 
                            <button class="btn btn-primary w-100" type="submit" name="submit">Sign In</button>
                        </div>
                        <br>
                        <h5>if you do not have account <a href="create_account.php">create account</a></h5>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
