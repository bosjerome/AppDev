<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$sql = "SELECT cart.id AS cart_id, cart.quantity, products.product_name, products.price, products.image_url
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id='$user_id'";
$result = $conn->query($sql);

// Handle item deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM cart WHERE id='$delete_id' AND user_id='$user_id'";
    $conn->query($delete_sql);
    header('Location: cart.php'); // Refresh the cart page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Colors */
        html, body {
            height: 100%; /* Full height for html and body */
            margin: 0; /* Remove default margins */
            display: flex; /* Enable flexbox */
            flex-direction: column; /* Stack elements vertically */
        }

        body {
            background-color: rgba(230, 230, 250, 0.7); /* Lavender with transparency */
        }

        p {
            text-align: center;
            color: rgba(148, 0, 211, 0.9);
        }
        .container {
            flex: 1; /* Allow the container to grow */
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

        /* Table Styles */
        .table {
            background-color: rgba(186, 85, 211, 0.2); /* Medium Orchid with transparency */
            border: 1px solid rgba(148, 0, 211, 0.3); /* Dark Violet border */
        }
        .table th, .table td {
            color: rgba(75, 0, 130, 0.9); /* Indigo for text */
        }
        .table th {
            background-color: rgba(138, 43, 226, 0.5); /* Slightly darker for header */
            color: white;
        }

        /* Buttons */
        .btn-primary {
            background-color: rgba(147, 112, 219, 0.9); /* Purple */
            border-color: rgba(147, 112, 219, 0.9); /* Same color border */
        }
        .btn-primary:hover, .btn-success:hover {
            background-color: rgba(106, 90, 205, 1); /* Slightly darker on hover */
            border-color: rgba(106, 90, 205, 1); 
        }

        /* Footer Styles */
        .footer {
            background-color: rgba(138, 43, 226, 0.9); /* BlueViolet */
            color: rgba(255, 255, 255, 0.8); /* White with transparency */
            padding: 20px;
            text-align: center;
            margin-top: auto; /* Push footer to the bottom */
        }
        .footer a {
            color: rgba(230, 230, 250, 1); /* Lavender */
            text-decoration: none;
        }
        .footer a:hover {
            color: rgba(255, 255, 255, 0.9); /* White on hover */
        }
        @media (max-width: 768px) {
            .footer {
                font-size: 0.9rem; /* Smaller font for mobile */
            }
            .navbar {
                padding: 0.5rem; /* Adjust padding for smaller screens */
            }
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
        <h2>Your Cart</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Delete</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    while ($row = $result->fetch_assoc()) {
                        $total = $row['quantity'] * $row['price'];
                        $total_price += $total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100px;"></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>₱<?php echo number_format($row['price'], 2); ?> </td>
                            <td>₱<?php echo number_format($total, 2); ?></td>
                            <td>
                                <a href="cart.php?delete_id=<?php echo $row['cart_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <h4>Total Price: ₱<?php echo number_format($total_price, 2); ?></h4>
            <a href="checkout.php" class="btn btn-success">Order</a> <!-- Order button -->
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Back to Homepage</a>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 NAKBAKA Clothing | All Rights Reserved</p>
        <a href="contact.php">Contact Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
