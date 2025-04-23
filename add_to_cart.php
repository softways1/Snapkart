<?php
session_start();
include("db.php");

$product_id = intval($_POST['product_id']);
$customer_email = $_SESSION['email']; 

$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['quantity'] > 0) {
    $new_qty = $row['quantity'] - 1;
    mysqli_query($conn, "UPDATE products SET quantity = $new_qty WHERE id = $product_id");

    $check = mysqli_query($conn, "SELECT * FROM cart WHERE customer_email = '$customer_email' AND product_id = $product_id");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE customer_email = '$customer_email' AND product_id = $product_id");
    } else {
        mysqli_query($conn, "INSERT INTO cart (customer_email, product_id, quantity, added_on) VALUES ('$customer_email', $product_id, 1, NOW())");
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo "<script>alert('Can\'t add. Product is out of stock!'); window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
    exit();
}
?>
