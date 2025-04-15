<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_email = $_SESSION['email'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    $image_name = $_FILES['product_image']['name'];
    $tmp_name = $_FILES['product_image']['tmp_name'];
    $upload_path = "product_images/" . $image_name;
    move_uploaded_file($tmp_name, $upload_path);

    $query = "INSERT INTO products (seller_email, name, price, description, quantity, category, subcategory, image_path) 
              VALUES ('$seller_email', '$product_name', '$price', '$description', '$quantity', '$category', '$subcategory', '$image_name')";

    if (mysqli_query($conn, $query)) {
        $success = "Product added successfully!";
    } else {
        $error = "Failed to add product!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-pink-200 via-orange-200 to-blue-300 min-h-screen">

    <div class="p-4 flex justify-end">
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
           class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow hover:opacity-90 transition">
            Logout
        </a>
    </div>

    <div class="flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-10 w-[500px] bg-gradient-to-r from-orange-400 via-pink-400 to-blue-400">
            <h2 class="text-3xl font-bold mb-6 text-center bg-gradient-to-r from-orange-500 via-pink-600 to-blue-500 text-transparent bg-clip-text">
                Add New Product
            </h2>

            <?php if (isset($success)): ?>
                <p class="text-green-600 font-semibold mb-4"><?php echo $success; ?></p>
            <?php elseif (isset($error)): ?>
                <p class="text-red-600 font-semibold mb-4"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="product_name" placeholder="Product Name" required class="w-full mb-4 p-2 rounded border text-black" />
                
                <input type="number" name="price" placeholder="Price" required class="w-full mb-4 p-2 rounded border text-black" />
                
                <textarea name="description" placeholder="Description" required class="w-full mb-4 p-2 rounded border text-black"></textarea>
                
                <input type="number" name="quantity" placeholder="Quantity" required class="w-full mb-4 p-2 rounded border text-black" />
                
                <select name="category" id="mainCategory" required class="w-full mb-4 p-2 rounded border text-black">
                    <option value="">-- Select Category --</option>
                    <option value="Electronics"> Electronics</option>
                    <option value="Clothing"> Clothing</option>
                    <option value="Grocery"> Grocery</option>
                    <option value="Books & Stationery"> Books & Stationery</option>
                    <option value="Home & Furniture"> Home & Furniture</option>
                    <option value="Other"> Other</option>
                </select>

                <select name="subcategory" id="subCategory" required class="w-full mb-4 p-2 rounded border text-black">
                    <option value="">-- Select Subcategory --</option>
                </select>

                <div class="flex items-center justify-center gap-3 mb-4">
                    <span class="text-xl"></span>
                    <input type="file" name="product_image" required class="w-[6cm] bg-gradient-to-r border-1 border-white from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90" />
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90">
                    Add Product
                </button>
            </form>
        </div>
    </div>
    
    <script>
        const subCategories = {
            "Electronics": ["Mobile Phone", "Laptop", "TV", "Smartwatch", "Other"],
            "Clothing": ["Men", "Women", "Kids", "Traditional", "Other"],
            "Grocery": ["Grains", "Spices", "Fruits", "Vegetables", "Other"],
            "Books & Stationery": ["Notebook", "Textbook", "Stationery", "Magazine", "Other"],
            "Home & Furniture": ["Sofa", "Bed", "Table", "Chair", "Other"],
            "Other": ["Other_Product"]
        };

        const mainCategory = document.getElementById("mainCategory");
        const subCategory = document.getElementById("subCategory");

        mainCategory.addEventListener("change", function () {
            const selected = this.value;
            subCategory.innerHTML = '<option value="">-- Select Subcategory --</option>';
            if (subCategories[selected]) {
                subCategories[selected].forEach(sub => {
                    const option = document.createElement("option");
                    option.value = sub;
                    option.textContent = sub;
                    subCategory.appendChild(option);
                });
            }
        });
    </script>

</body>
</html>
