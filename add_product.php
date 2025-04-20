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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | SnapKart</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        #product_image {
            display: none; /* Hide the default file input */
        }
    </style>
</head>
<body class="relative bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 h-screen flex justify-center items-start pt-14">

    <!-- Logout Button pinned to top right -->
    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"
       class="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-red-400 via-pink-500 to-purple-500 text-white rounded-lg font-semibold hover:opacity-90 transition text-sm z-50">
        Logout
    </a>

    <div class="flex flex-col h-auto w-[13cm] p-6 bg-gradient-to-r from-orange-300 via-pink-300 to-blue-300 rounded-2xl shadow-2xl border border-gray-300">
        <h2 class="text-3xl font-bold text-center mb-6 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text animate-bounce">
            Add New Product
        </h2>

        <?php if (isset($success)): ?>
            <p class="text-center font-semibold text-lg text-green-600 mb-4"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-center font-semibold text-lg text-red-600 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-2">
            <div>
                <label for="product_name" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Product Name:
                </label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required
                       class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500">
            </div>

            <div>
                <label for="price" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Price:
                </label>
                <input type="number" id="price" name="price" placeholder="Enter price" required
                       class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500">
            </div>

            <div>
                <label for="description" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Description:
                </label>
                <textarea id="description" name="description" placeholder="Enter description" required
                          class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500"></textarea>
            </div>

            <div>
                <label for="quantity" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Quantity:
                </label>
                <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required
                       class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500">
            </div>

            <div>
                <label for="mainCategory" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Category:
                </label>
                <select name="category" id="mainCategory" required
                        class="w-full border border-gray-400 rounded p-2 text-black">
                    <option value="">-- Select Category --</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Grocery">Grocery</option>
                    <option value="Books & Stationery">Books & Stationery</option>
                    <option value="Home & Furniture">Home & Furniture</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="subCategory" class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Subcategory:
                </label>
                <select name="subcategory" id="subCategory" required
                        class="w-full border border-gray-400 rounded p-2 text-black">
                    <option value="">-- Select Subcategory --</option>
                </select>
            </div>

            <div>
                <label class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text block mb-1">
                    Product Image:
                </label>
                <button type="button" id="uploadButton" class="w-full bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90">
                    Add Image
                </button>
                <input type="file" id="product_image" name="product_image" required class="hidden">
            </div>

            <button type="submit"
                    class="w-full mt-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90">
                Add Product
            </button>
        </form>
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
        const uploadButton = document.getElementById("uploadButton");
        const fileInput = document.getElementById("product_image");

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

        uploadButton.addEventListener("click", function () {
            fileInput.click();
        });
    </script>

</body>
</html>
