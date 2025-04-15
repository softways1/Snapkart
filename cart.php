<?php
session_start();
include("db.php");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$products = [];
$total_price = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $row['cart_quantity'] = $cart[$id];
        $row['subtotal'] = $cart[$id] * $row['price'];
        $total_price += $row['subtotal'];
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 p-4">

  <div class="flex justify-between items-center mb-6 px-6 py-3 bg-gradient-to-r from-orange-400 via-pink-500 to-purple-500 text-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold">My Cart</h1>
    <a href="customer_home.php" class="bg-gradient-to-r from-orange-400 via-pink-400 to-blue-600 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow hover:opacity-90">
    Back to Dashboard
    </a>
  </div>

  <?php if (!empty($products)) { ?>
    <div class="space-y-4 max-w-4xl mx-auto">
      <?php foreach ($products as $product) { ?>
        <div class="bg-gradient-to-r from-pink-100 via-purple-100 to-pink-200 rounded-2xl shadow-md p-4 w-full max-w-md mx-auto">
  <div class="flex gap-4 items-center">
    <img src="product_images/<?php echo $product['image_path']; ?>" class="w-16 h-16 rounded-lg shadow">
    <div class="text-sm">
      <div class="text-blue-800 font-semibold text-base"><?php echo $product['name']; ?></div>
      <div class="text-gray-600">Qty: <?php echo $product['cart_quantity']; ?></div>
      <div class="text-gray-600">₹<?php echo number_format($product['price'], 2); ?> × <?php echo $product['cart_quantity']; ?> = 
        <span class="font-semibold text-black">₹<?php echo $product['subtotal']; ?></span>
      </div>
    </div>
  </div>
</div>

        </div>
      <?php } ?>

<div class="text-right text-lg font-bold text-purple-900 mt-4">Total: ₹<?php echo $total_price; ?></div>

<form method="post" action="place_order.php" class="text-center mt-4">
  <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-2 rounded-full shadow hover:opacity-90">
    Place Order & Pay Now
  </button>
</form>

    </div>
  <?php } else { ?>
    <div class="text-center mt-20 text-purple-900 text-lg font-semibold"> Your cart is empty!</div>
  <?php } ?>

</body>
</html>
