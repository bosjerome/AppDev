<?php
session_start();
include 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to index or home page
            header('Location: index.php');
            exit;
        } else {
            echo "<div class='alert alert-danger'>Invalid password!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No user found!</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: rgba(230, 230, 250, 0.7); /* Lavender with transparency */
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(148, 0, 211, 0.8); /* Dark Violet */
        }
        .navbar-brand, .nav-link {
            color: white;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.7); /* Lighten on hover */
        }

        /* Centered Text */
        h2 {
            text-align: center;
            color: rgba(75, 0, 130, 0.9); /* Indigo */
        }
        
        /* Form Styles */
        .form-control {
            background-color: rgba(186, 85, 211, 0.2); /* Medium Orchid */
            border: 1px solid rgba(148, 0, 211, 0.3); /* Dark Violet border */
        }

        .form-control:focus {
            border-color: rgba(75, 0, 130, 1); /* Indigo border on focus */
            box-shadow: 0 0 5px rgba(75, 0, 130, 0.5); /* Shadow effect */
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

        .btn-secondary {
            background-color: rgba(148, 0, 211, 0.8); /* Dark Violet */
            border-color: rgba(148, 0, 211, 0.8);
        }
        .btn-secondary:hover {
            background-color: rgba(106, 90, 205, 1); /* Slightly darker on hover */
            border-color: rgba(106, 90, 205, 1); 
        }

        /* Footer Styles */
        footer {
            background-color: rgba(148, 0, 211, 0.8);
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: auto; /* Pushes footer to the bottom */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-violet">
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
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php">Order History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
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
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="mt-3 text-center">
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vintage Hub. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
