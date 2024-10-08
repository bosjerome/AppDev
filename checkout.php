<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Calculate total amount
    $sql = "SELECT SUM(products.price * cart.quantity) AS total_amount FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.user_id = '$user_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];

    // Create new order
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total_amount')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert order items
        $sql = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
        $cart_items = $conn->query($sql);

        while ($item = $cart_items->fetch_assoc()) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $sql = "INSERT INTO order_items (order_id, product_id, quantity)
                    VALUES ('$order_id', '$product_id', '$quantity')";
            $conn->query($sql);
        }

        // Clear cart after checkout
        $sql = "DELETE FROM cart WHERE user_id = '$user_id'";
        $conn->query($sql);

        echo "Order placed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Please log in to proceed with the checkout.";
}
?>
