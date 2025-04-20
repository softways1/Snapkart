<?php
session_start();
include("db.php");

if (isset($_POST['login'])) {
    $usertype = $_POST['usertype'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($usertype == 'customer') {
        $query = "SELECT * FROM customers WHERE email='$email' AND password='$password'";
    } else {
        $query = "SELECT * FROM sellers WHERE email='$email' AND password='$password'";
    }

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $email;
        $_SESSION['usertype'] = $usertype;
        $_SESSION['name'] = ($usertype == 'customer') ? $row['name'] : $row['owner_name'];

        if ($usertype == 'customer') {
            header("Location: customer_home.php");
        } else {
            header("Location: seller_home.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SnapKart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200">

    <!-- Header -->
    <div class="flex justify-center items-center w-auto h-[1.2cm] bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
            Welcome To SnapKart
        </h1>
    </div>

    <!-- Login Form -->
    <div class="flex justify-center items-start pt-14 min-h-screen">
        <div class="flex flex-col h-auto w-[13cm] p-6 bg-gradient-to-r from-orange-300 via-pink-300 to-blue-300 rounded-2xl shadow-2xl border border-gray-300">
            
            <h2 class="text-3xl font-bold text-center mb-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text animate-bounce">
                Login
            </h2>

            <form method="POST" action="login.php" class="space-y-3">
                <!-- Radio buttons -->
                <div class="flex items-center gap-4">
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        I am a:
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="usertype" value="customer" required class="mr-1" />
                        <span class="bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text font-medium">Customer</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="usertype" value="seller" required class="mr-1" />
                        <span class="bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text font-medium">Seller</span>
                    </label>
                </div>

                <!-- Email -->
                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
                        Email:
                    </label>
                    <input type="email" name="email" required placeholder="Enter your email"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <!-- Password -->
                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
                        Password:
                    </label>
                    <input type="password" name="password" required placeholder="Enter your password"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <!-- Submit Button -->
                <button type="submit" name="login"
                    class="w-full mt-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90 transition">
                    Login
                </button>
            </form>

            <!-- Register Links -->
            <div class="mt-6 p-4 rounded-xl">
                <p class="text-center font-semibold text-lg bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text mb-2 animate-bounce">
                    Don't have an account?
                </p>
                <div class="flex justify-center gap-4">
                    <a href="customer_register.php"
                        class="px-4 py-2 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white rounded-lg font-semibold hover:opacity-90 transition">
                        Register as Customer
                    </a>
                    <a href="seller_register.php"
                        class="px-4 py-2 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white rounded-lg font-semibold hover:opacity-90 transition">
                        Register as Seller
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
