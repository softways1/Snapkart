<?php
session_start();
include("db.php");

$seller_email = $_SESSION['email'];

if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    mysqli_query($conn, "DELETE FROM products WHERE id = '$product_id' AND seller_email = '$seller_email'");
}

if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = mysqli_real_escape_string($conn, $_POST['new_quantity']);

    if (!empty($new_quantity)) {
        $old_qty_result = mysqli_query($conn, "SELECT quantity FROM products WHERE id = '$product_id' AND seller_email = '$seller_email'");
        if ($old_row = mysqli_fetch_assoc($old_qty_result)) {
            $updated_qty = $old_row['quantity'] + (int)$new_quantity;
            $update_sql = "UPDATE products SET quantity = '$updated_qty' WHERE id = '$product_id' AND seller_email = '$seller_email'";
            mysqli_query($conn, $update_sql);
        }
    }
}

$search = isset($_GET['search']) ? $_GET['search'] : "";
$query = "SELECT * FROM products WHERE seller_email = '$seller_email'";
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}
$result = mysqli_query($conn, $query);

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE seller_email = '$seller_email' AND name LIKE '%$search%'");
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 min-h-screen p-6">

    <div class="flex items-center justify-between mb-6 px-4">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-500 text-transparent bg-clip-text">
            My Products
        </h1>

        <form method="get" class="flex-1 flex justify-center">
            <div class="bg-gradient-to-r from-pink-200 via-pink-300 to-pink-400 p-1 rounded-full shadow-lg flex items-center w-full max-w-md">
                <input type="text" name="search" placeholder="Search product..." value="<?php echo $search; ?>"
                    class="flex-grow px-4 py-2 rounded-l-full bg-white text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-pink-400" />
                <button type="submit"
                    class="bg-gradient-to-r from-pink-500 to-pink-700 text-white font-semibold px-5 py-2 rounded-r-full hover:opacity-90 transition">
                    Search
                </button>
            </div>
        </form>

        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
           class="ml-4 bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
            Logout
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 px-4">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <div class="bg-gradient-to-br from-pink-200 via-orange-100 to-purple-100 rounded-xl shadow-md p-4 flex flex-col items-center space-y-2 text-xs w-full">
                <img src="product_images/<?php echo $row['image_path']; ?>" class="w-16 h-16 rounded shadow" alt="Product Image">
                <h2 class="font-semibold text-blue-800 text-center text-sm"><?php echo $row['name']; ?></h2>
                <p class="text-gray-700 text-center"><?php echo $row['description']; ?></p>
                <p class="text-pink-700 font-medium text-sm">₹<?php echo $row['price']; ?> | Qty: <?php echo $row['quantity']; ?></p>

                <form method="POST" class="w-full flex flex-col items-center space-y-1" onsubmit="return confirmDelete(event)">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <input type="number" name="new_quantity" placeholder="New Quantity"
                        class="w-full text-sm px-2 py-1 rounded-full bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 text-center">

                    <div class="flex justify-center space-x-2 mt-1">
                        <button type="submit" name="update"
                            class="bg-yellow-400 text-black text-xs font-semibold px-3 py-1 rounded hover:bg-yellow-500 transition">
                            Update
                        </button>
                        <button type="submit" name="delete"
                            class="bg-gradient-to-r from-red-400 to-pink-500 text-white text-xs font-semibold px-3 py-1 rounded hover:opacity-90 transition">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>

    <script>
        function confirmDelete(event) {
            const btn = event.submitter;
            if (btn && btn.name === "delete") {
                return confirm("Are you sure you want to delete this product?");
            }
            return true;
        }
    </script>

</body>
</html>
