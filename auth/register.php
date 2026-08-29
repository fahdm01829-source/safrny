<?php
include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/countries.php');

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $country = trim($_POST["country"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if (empty($fullname)) {
        $errors['fullname'] = "Full name is required.";
    }
    if (empty($phone)) {
        $errors['phone'] = "Mobile number is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }
    if (empty($country)) {
        $errors['country'] = "Please select your country.";
    }
    if (empty($nationality)) {
        $errors['nationality'] = "Please select your nationality.";
    }
    if (empty($address)) {
        $errors['address'] = "Address is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }
    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }
    if (!isset($_POST["terms"])) {
        $errors['terms'] = "You must agree to the Terms & Conditions and Privacy Policy.";
    }

    if (empty($errors)) {
        $checkQuery = "SELECT id FROM users WHERE email = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        if (mysqli_num_rows($checkResult) > 0) {
            $errors['email'] = "An account with this email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $status = "Active";
            $insertQuery = "
                INSERT INTO users
                (
                    fullname,
                    phone,
                    email,
                    password,
                    country,
                    nationality,
                    address,
                    status,
                    created_at,
                    last_login
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NULL)
            ";

            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param(
                $insertStmt,
                "ssssssss",
                $fullname,
                $phone,
                $email,
                $hashedPassword,
                $country,
                $nationality,
                $address,
                $status
            );

            if (mysqli_stmt_execute($insertStmt)) {
                header("Location:login.php");
                exit;
            } else {
                $errors['general'] = "Something went wrong. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="icon" type="image/png" href="/safrny/public/favicon.png">
    <link rel="stylesheet" href="/safrny/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/safrny/assets/css/register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
</head>

<body>

    <div class="register-page">
        <div class="register-left">
            <div class="logo">
                <a href="/safrny/index.php"><img src="/safrny/assets/images/Icon.png" class="logo-icon"
                        alt="Safrny"></a>
            </div>
            <div class="quote-container">
                <div class="quote">
                    "The world is a book, and those who do not travel read only one page."
                </div>
                <div class="quote-author">
                    — Saint Augustine
                </div>
            </div>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">45K+</div>
                    <div class="stat-label">Travelers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">78</div>
                    <div class="stat-label">Countries</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">4.9★</div>
                    <div class="stat-label">Rating</div>
                </div>
            </div>
        </div>
        <div class="register-right">
            <a href="/safrny/index.php" class="mobile-home">
                <img src="/safrny/assets/images/Icon.png" alt="Safrny">
            </a>
            <div class="register-form-container">
                <h1 class="register-title">
                    Create your account
                </h1>
                <p class="register-subtitle">
                    Join Safrny and start planning your next adventure.
                </p>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" name="fullname" class="form-control" placeholder="Khalid Al-Mansouri"
                            value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                            required>
                        <?php if (isset($errors['fullname'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['fullname']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Mobile Number <span class="required">*</span>
                        </label>
                        <input type="tel" name="phone" class="form-control" placeholder="+966 55 000 0000"
                            value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                            required>
                        <?php if (isset($errors['phone'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['phone']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required>
                        <?php if (isset($errors['email'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['email']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="two-columns">
                        <div class="form-group">
                            <label class="form-label">
                                Country <span class="required">*</span>
                            </label>
                            <select name="country" class="form-control" required>
                                <option value="" disabled selected>Select your country</option>
                                <?php foreach ($countries as $code => $name) { ?>
                                    <option value="<?php echo $code; ?>">
                                        <?php echo $name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <?php if (isset($errors['country'])) { ?>
                                <div class="field-error">
                                    <?php echo $errors['country']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Nationality <span class="required">*</span>
                            </label>
                            <select name="nationality" class="form-control" required>
                                <option value="" disabled selected>Select your nationality</option>
                                <?php foreach ($nationalities as $nationality) { ?>
                                    <option value="<?php echo $nationality; ?>">
                                        <?php echo $nationality; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <?php if (isset($errors['nationality'])) { ?>
                                <div class="field-error">
                                    <?php echo $errors['nationality']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Address <span class="required">*</span>
                        </label>
                        <textarea name="address" class="form-control" placeholder="Enter your address"
                            required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                        <?php if (isset($errors['address'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['address']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="At least 8 characters" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['password']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Confirm Password <span class="required">*</span>
                        </label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control"
                                placeholder="Repeat your password" required>
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('confirmPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['confirm_password'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['confirm_password']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="terms">
                        <div class="terms-content">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms">
                                I agree to the
                                <a href="#">Terms & Conditions</a>
                                and
                                <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                        <?php if (isset($errors['terms'])) { ?>
                            <div class="field-error">
                                <?php echo $errors['terms']; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <button type="submit" class="register-btn">
                        Create Account
                    </button>
                    <div class="login-text">
                        Already have an account?
                        <a href="./login.php">
                            Sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector("i");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");

            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

</body>

</html>