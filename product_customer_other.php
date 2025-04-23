<?php
session_start();
include("db.php");
$customer_email = $_SESSION['email']; // assuming user is logged in
$cart_count = 0;

$result = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE customer_email = '$customer_email'");
$row = mysqli_fetch_assoc($result);
$cart_count = $row['total'] ?? 0;
$search = isset($_GET['search']) ? $_GET['search'] : "";

$query = "SELECT * FROM products WHERE category = 'Other'";

if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}

$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Other Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-r from-orange-200 via-pink-400 to-purple-300">
  <div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-500  flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 mb-2 shadow-md rounded-b-xl relative gap-3">
    
    <form method="get" class="flex w-auto items-center mx-auto">
      <input type="text" name="search" placeholder="Search product..." value="<?php echo htmlspecialchars($search); ?>" class="px-3 py-1 rounded-l-full border border-gray-300 text-sm focus:outline-none">
      <button type="submit" class="italic px-4 py-1 bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white rounded-r-full hover:bg-pink-600 transition text-sm">Search</button>
    </form>

    <div class="flex items-center gap-2">
    <a href="cart.php" class="italic bg-gradient-to-r from-yellow-400 via-red-400 to-pink-400 text-white px-3 py-1 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
             Cart (<?php echo $cart_count; ?>)
        </a>
      <a href="customer_home.php" class="italic bg-gradient-to-r from-green-400 via-blue-400 to-purple-400 text-white px-4 py-2 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
        Dashboard
      </a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
         class="italic bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
          Logout
      </a>
    </div>
  </div>

  <?php if (mysqli_num_rows($result) > 0) { ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 px-4">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="bg-gradient-to-br from-pink-200 via-orange-100 to-purple-100 rounded-xl shadow-md p-4 flex flex-col items-center space-y-2 text-xs w-full">
          <img src="product_images/<?php echo $row['image_path']; ?>" class="w-16 h-16 rounded shadow" alt="Product Image">
          <h2 class="font-semibold text-blue-800 text-center text-sm"><?php echo $row['name']; ?></h2>
          <p class="text-gray-700 text-center"><?php echo $row['description']; ?></p>
          <p class="text-pink-700 font-medium text-sm">₹<?php echo $row['price']; ?> | Qty: <?php echo $row['quantity']; ?></p>
          <form method="POST" action="add_to_cart.php" class="mt-2">
              <input type="hidden" name="product_id" value="' . $row['id'] . '">
              <button type="submit" class="bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500  text-white px-3 py-1 rounded-full text-sm hover:bg-purple-700 transition">
                  Add to Cart 
              </button>
          </form>
        </div>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="mt-20 text-center p-6 rounded-xl bg-white bg-opacity-60 backdrop-blur-md shadow text-purple-900 text-lg font-semibold max-w-lg mx-auto">
      <p class="mb-2">Sorry, out of stock.</p>
      <p>Inconvenience is highly regretted. Please check back after a few days.</p>
    </div>
  <?php } ?>

</body>
</html>
