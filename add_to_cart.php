<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    $check_sql = "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If product already exists, update the quantity
        $row = $check_result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $update_sql = "UPDATE cart SET quantity='$new_quantity' WHERE user_id='$user_id' AND product_id='$product_id'";
        
        if ($conn->query($update_sql) === TRUE) {
            // Redirect back to index.php after successful update
            header('Location: index.php?message=Product updated in cart');
            exit;
        } else {
            echo "Error updating cart: " . $conn->error;
        }
    } else {
        // If product doesn't exist, insert it into the cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
        
        if ($conn->query($sql) === TRUE) {
            // Redirect back to index.php after successful insert
            header('Location: index.php?message=Product added to cart');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
} else {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit;
}
?>
