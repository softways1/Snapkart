<?php
session_start();
include('db.php');
$customer_email = $_SESSION['email']; 
$cart_count = 0;

$result = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE customer_email = '$customer_email'");
$row = mysqli_fetch_assoc($result);
$cart_count = $row['total'] ?? 0;

if (!isset($_SESSION['email']) || $_SESSION['usertype'] != 'customer') {
    header("Location: login.php");
    exit();
}

$customer_name = $_SESSION['name'] ?? 'Customer';

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%' ORDER BY added_on DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY added_on DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('categoryMenu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('categoryMenu');
            const button = document.getElementById('hamburgerButton');
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</head>
<body class="bg-gradient-to-r from-orange-200 via-pink-400 to-blue-300 min-h-screen flex flex-col">

<div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-500 text-white flex flex-col sm:flex-row  px-4 py-2 shadow-md rounded-b-xl relative gap-3">

    <div class="flex items-center space-x-3">
        <button id="hamburgerButton" onclick="toggleMenu()" class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm italic font-semibold shadow hover:opacity-90 transition">
            CATEGORY
        </button>
        <h1 class="text-xl  italic font-bold">Welcome, <?php echo htmlspecialchars($customer_name); ?> </h1>
    </div>

    <form method="GET" class="flex flex-grow justify-center">
        <input type="text" name="search" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_query); ?>"
               class="w-full max-w-lg px-4 py-2 rounded-l-full border border-gray-300 text-black focus:outline-none">
        <button type="submit"
                class="bg-gradient-to-r from-orange-400 via-pink-500 to-purple-500 text-white italic px-5 py-2 rounded-r-full font-semibold hover:opacity-90 transition">
            Search
        </button>
    </form>

    <div class="flex items-center gap-2 justify-end">
        <a href="cart.php" class="bg-gradient-to-r from-yellow-400 via-red-400 to-pink-400 text-white px-3 py-1 rounded-full text-sm font-semibold italic shadow hover:opacity-90 transition">
            Cart (<?php echo $cart_count; ?>)
        </a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
           class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white italic px-3 py-1 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
            Logout
        </a>
    </div>
</div>

<div id="categoryMenu" class="hidden absolute top-20 left-6  bg-white bg-opacity-90 rounded-lg shadow-xl w-48">
    <ul class="flex flex-col text-left text-black py-2">
        <li><a href="product_customer_electronics.php" class="block px-4 py-2 hover:text-purple-600 font-semibold">Electronics</a></li>
        <li><a href="product_customer_clothing.php" class="block px-4 py-2 hover:text-purple-600">Clothing</a></li>
        <li><a href="product_customer_grocery.php" class="block px-4 py-2 hover:text-purple-600">Grocery</a></li>
        <li><a href="product_customer_books.php" class="block px-4 py-2 hover:text-purple-600">Books & Stationery</a></li>
        <li><a href="product_customer_home.php" class="block px-4 py-2 hover:text-purple-600">Home & Furniture</a></li>
        <li><a href="product_customer_other.php" class="block px-4 py-2 hover:text-purple-600">Other</a></li>
    </ul>
</div>

<div class="grid grid-cols-5 gap-4 px-4 mt-4">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="bg-gradient-to-br from-orange-200 via-pink-200 to-blue-200 rounded-xl shadow-md p-3 text-center">
                <img src="product_images/' . htmlspecialchars($row['image_path']) . '" alt="Product Image" class="w-24 h-24 object-cover mx-auto rounded-md mb-2">
                <h3 class="text-sm font-bold text-blue-800">' . htmlspecialchars($row['name']) . '</h3>
                <p class="text-xs text-gray-700 truncate">' . htmlspecialchars($row['description']) . '</p>
                <p class="text-green-700 font-semibold text-sm mt-1">₹' . htmlspecialchars($row['price']) . '</p>
                <p class="text-xs text-gray-7">Category: ' . htmlspecialchars($row['category']) . '</p>
                <p class="text-xs text-gray-700">Sub: ' . htmlspecialchars($row['subcategory']) . '</p>
                <p class="text-xs text-gray-700">Quantity: ' . htmlspecialchars($row['quantity']) . '</p>
                <p class="text-xs text-gray-700 mt-1">Added: ' . date('d M Y', strtotime($row['added_on'])) . '</p>
                <form method="POST" action="add_to_cart.php" class="mt-2">
                    <input type="hidden" name="product_id" value="' . $row['id'] . '">
                    <button type="submit" class="bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white px-3 py-1 rounded-full text-sm hover:bg-purple-700 transition">
                        Add to Cart 
                    </button>
                </form>
            </div>';
        }
    } else {
        echo '<p class="text-center text-xl col-span-3 text-red-600 font-semibold">No products found.</p>';
    }
    ?>
</div>

</body>
</html>
