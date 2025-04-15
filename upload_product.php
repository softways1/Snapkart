<?php
session_start();
include("db.php");

if (isset($_POST['upload'])) {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $seller_email = $_SESSION['email'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $folder = "product_images/" . $image;

    if (move_uploaded_file($tmp, $folder)) {
        $query = "INSERT INTO products (product_name, category, price, quantity, description, image, seller_email) 
                  VALUES ('$product_name', '$category', '$price', '$quantity', '$description', '$image', '$seller_email')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Product added successfully!'); window.location.href='add_product.php';</script>";
        } else {
            echo "<script>alert('Failed to add product.');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.');</script>";
    }
}
?>
