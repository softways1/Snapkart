<?php
session_start();
include("db.php");

$seller_name = $_SESSION['name'] ?? 'Seller';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 min-h-screen flex flex-col">

    <div class="flex justify-between items-center px-8 py-4 shadow-md bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-500 text-transparent bg-clip-text">
            Welcome, <?php echo htmlspecialchars($seller_name); ?>!
        </h1>
        <div class="mb-4 flex justify-end">
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
            class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
                Logout
            </a>
        </div>
    </div>
    <div class="flex-grow flex justify-center items-center">
    <div class="text-center">
        <h2 class="text-2xl font-semibold mb-6 text-blue-900">Manage Your Store 🛒</h2>

        <a href="add_product.php" class="bg-gradient-to-r from-pink-400 to-purple-500 text-white px-6 py-3 rounded-2xl shadow-lg text-lg font-semibold hover:scale-105 transition transform inline-block mr-4">
            Add Product
        </a>
        <a href="display_product_seller.php" class="bg-gradient-to-r from-pink-400 to-purple-500 text-white px-6 py-3 rounded-2xl shadow-lg text-lg font-semibold hover:scale-105 transition transform inline-block">
            Display My Products
        </a>
    </div>
</div>
</body>
</html>
