<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Update the order status to 'accepted'
    $stmt = $conn->prepare("UPDATE orders SET status = 'accepted' WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order #$order_id has been accepted.";
    } else {
        $_SESSION['message'] = "Error updating order: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();

// Redirect back to admin.php
header("Location: admin.php");
exit();
