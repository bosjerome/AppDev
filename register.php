<?php
include 'config.php'; // Include your database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address']; // Get the address from the form

    // Check if password and confirm password match
    if ($password === $confirm_password) {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user information into the database, including the address
        $sql = "INSERT INTO users (username, email, password, address) VALUES ('$username', '$email', '$hashed_password', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful! You can now log in.";
            header('Location: login.php'); // Redirect to the login page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Passwords do not match!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Colors */
        body {
            background-color: rgba(230, 230, 250, 0.7); /* Lavender with transparency */
        }

        /* Navbar */
        .navbar {
            background-color: rgba(148, 0, 211, 0.8); /* Dark Violet with some transparency */
        }
        .navbar-brand, .nav-link {
            color: white;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.7); /* Lighten on hover */
        }

        /* Buttons */
        .btn-primary {
            background-color: rgba(147, 112, 219, 0.9); /* Purple */
            border-color: rgba(147, 112, 219, 0.9); /* Same color border */
        }
        .btn-primary:hover {
            background-color: rgba(106, 90, 205, 1); /* Slightly darker on hover */
            border-color: rgba(106, 90, 205, 1); 
        }

        /* Footer */
        .footer {
            background-color: rgba(138, 43, 226, 0.9); /* BlueViolet */
            color: rgba(255, 255, 255, 0.8); /* White with transparency */
            padding: 20px;
            text-align: center;
        }
        .footer a {
            color: rgba(230, 230, 250, 1); /* Lavender */
            text-decoration: none;
        }
        .footer a:hover {
            color: rgba(255, 255, 255, 0.9); /* White on hover */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <h1 class="navbar-brand" href="#">Vintage Hub</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php">Order History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <div class="form-group mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
    <br>

    <div class="footer">
        <p>&copy; 2024 Vintage Hub | All Rights Reserved</p>
        <a href="contact.php">Contact Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
