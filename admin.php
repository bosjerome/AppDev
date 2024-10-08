<?php
session_start();
include 'config.php';

// Display any session message
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-info'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']);  // Clear the message after displaying it
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $product_name, $description, $price, $image_url);

    if ($stmt->execute()) {
        $message = "Product added successfully.";
    } else {
        $message = "Error adding product: " . $conn->error;
    }
    $stmt->close();
}

// Fetch pending orders
$sql_orders = "SELECT orders.id, orders.order_date, orders.total_amount, orders.status, users.username 
               FROM orders 
               JOIN users ON orders.user_id = users.id 
               WHERE orders.status = 'pending' 
               ORDER BY order_date DESC";
$pending_orders = $conn->query($sql_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgba(230, 230, 250, 0.7); /* Lavender with transparency */
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    
    <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>

    <h3>Add New Product</h3>
    <form action="admin.php" method="post">
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="image_url" class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="text" name="stock" class="form-control" required>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
    </form>

    <h3 class="mt-5">Pending Orders</h3>
    <?php if ($pending_orders->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $pending_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>$<?php echo number_format($order['stock']); ?></td>
                        <td>
                            <form action="process_order.php" method="post" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="btn btn-success">Accept Order</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending orders found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
