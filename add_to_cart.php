<?php
session_start();
include("db.php");

$product_id = intval($_POST['product_id']);

$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['quantity'] > 0) {
    $new_qty = $row['quantity'] - 1;
    mysqli_query($conn, "UPDATE products SET quantity = $new_qty WHERE id = $product_id");

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    
    echo "<script>alert('Can\'t add. Product is out of stock!'); window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
    exit();
}
?>
