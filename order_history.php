<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Hub - Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            background-color: rgba(230, 230, 250, 0.7);
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(148, 0, 211, 0.8);
        }
        .navbar-brand, .nav-link {
            color: white;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Centered Text */
        h2, h4 {
            text-align: center;
            color: rgba(75, 0, 130, 0.9);
        }
        p {
            text-align: center;
            color: rgba(148, 0, 211, 0.9);
        }

        /* Order Items */
        .order {
            margin-bottom: 20px;
            padding: 15px;
            background-color: rgba(186, 85, 211, 0.2);
            border: 1px solid rgba(148, 0, 211, 0.3);
            border-radius: 5px;
            text-align: center;
        }
        .order img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        /* Buttons */
        .btn-primary {
            background-color: rgba(147, 112, 219, 0.9);
            border-color: rgba(147, 112, 219, 0.9);
        }
        .btn-primary:hover {
            background-color: rgba(106, 90, 205, 1);
            border-color: rgba(106, 90, 205, 1); 
        }

        /* Footer */
        .footer {
            background-color: rgba(138, 43, 226, 0.9);
            color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            text-align: center;
        }
        .footer a {
            color: rgba(230, 230, 250, 1);
            text-decoration: none;
        }
        .footer a:hover {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            h2 {
                font-size: 1.5rem;
            }
            h4 {
                font-size: 1.2rem;
            }
            p, .order {
                font-size: 0.9rem;
            }
            .footer {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <h1 class="navbar-brand">Vintage Hub</h1>
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

    <div class="content container">
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
            $orders = $conn->query($sql);

            if ($orders->num_rows > 0) {
                echo "<h2>Your Order History</h2>";

                while ($order = $orders->fetch_assoc()) {
                    echo "<div class='order'>";
                    echo "<h4>Order #" . $order['id'] . " - Placed on: " . $order['order_date'] . "</h4>";
                    echo "Total Amount: ₱" . number_format($order['total_amount'], 2) . "<br>";
                    echo "Status: " . htmlspecialchars($order['status']) . "<br>";

                    $order_id = $order['id'];
                    $sql_items = "SELECT order_items.quantity, products.product_name, products.price, products.image_url FROM order_items
                                  JOIN products ON order_items.product_id = products.id
                                  WHERE order_items.order_id = '$order_id'";
                    $order_items = $conn->query($sql_items);

                    if ($order_items->num_rows > 0) {
                        echo "<h5 class='mt-3'>Items in this order:</h5>";
                        echo "<ul class='list-unstyled'>";
                        while ($item = $order_items->fetch_assoc()) {
                            echo "<li class='d-flex justify-content-center align-items-center mb-2'>";
                            echo "<img src='" . htmlspecialchars($item['image_url']) . "' alt='" . htmlspecialchars($item['product_name']) . "' class='me-2'>";
                            echo htmlspecialchars($item['product_name']) . " - " . $item['quantity'] . " units @ ₱" . number_format($item['price'], 2) . " each";
                            echo "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No items found for this order.</p>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>You have not placed any orders yet.</p>";
            }

            echo '<div class="text-center mt-4"><a href="index.php" class="btn btn-primary">Back to Homepage</a></div>';
        } else {
            echo '<div class="text-center mt-4"><p>Please log in to view your order history.</p></div>';
        }
        ?>
    </div>

    <div class="footer">
        <p>&copy; 2024 Vintage Hub | All Rights Reserved</p>
        <a href="contact.php">Contact Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>