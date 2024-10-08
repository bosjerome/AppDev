<?php
session_start();
include 'config.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vintage Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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

        /* Product Cards */
        .card {
            background-color: rgba(186, 85, 211, 0.2); /* Medium Orchid with transparency */
            border: 1px solid rgba(148, 0, 211, 0.3); /* Dark Violet border */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover */
            height: 100%; /* Equal height cards */
        }
        .card:hover {
            background-color: rgba(186, 85, 211, 0.4); /* More opacity on hover */
            transform: translateY(-5px); /* Slight lift effect */
        }
        .card-title {
            color: rgba(75, 0, 130, 0.9); /* Indigo for card title */
            font-size: 1.1rem; /* Adjust font size for better readability */
        }
        .card-text {
            color: rgba(148, 0, 211, 0.9); /* Dark violet for text */
            font-size: 0.9rem; /* Slightly smaller font for description */
        }
        .card-img-top {
            height: 380px; /* Fixed height for images */
            object-fit: cover; /* Ensure images cover the area without distortion */
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem; /* Smaller font size for mobile */
            }
            .card-title {
                font-size: 1rem;
            }
            .card-text {
                font-size: 0.8rem;
            }
            .footer {
                font-size: 0.9rem; /* Smaller font for mobile */
            }
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
        <h1 class="text-center mb-4">Our Latest Collection</h1>

        <!-- Confirmation Message -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // Fetch and display products
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "
                    <div class='col'>
                        <div class='card h-100'>
                            <img src='" . htmlspecialchars($row['image_url']) . "' class='card-img-top' alt='" . htmlspecialchars($row['product_name']) . "'>
                            <div class='card-body d-flex flex-column'>
                                <h5 class='card-title'>" . htmlspecialchars($row['product_name']) . "</h5>
                                <p class='card-text flex-grow-1'>" . htmlspecialchars($row['description']) . "</p>
                                <p class='card-text'><strong>Price: ₱" . htmlspecialchars($row['price']) . "</strong></p>
                                <form action='add_to_cart.php' method='post' class='mt-auto'>
                                    <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                                    <div class='input-group mb-3'>
                                        <input type='number' name='quantity' value='1' min='1' class='form-control' required>
                                        <button type='submit' class='btn btn-primary'>Add to Cart</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center'>No products available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <div class="footer mt-5">
        <p>&copy; 2024 Vintage Hub | All Rights Reserved</p>
        <a href="contact.php">Contact Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>