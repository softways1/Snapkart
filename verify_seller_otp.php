<?php
session_start();
include('db.php');

$show_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        $data = $_SESSION['temp_seller'];
        $shop_name = $data['shop_name'];
        $owner_name = $data['owner_name'];
        $email = $data['email'];
        $password = $data['password'];
        $phone = $data['phone'];
        $address = $data['address'];
        $pincode = $data['pincode'];

        $insert = mysqli_query($conn, "INSERT INTO sellers (shop_name, owner_name, email, password, phone, address, pincode) 
            VALUES ('$shop_name', '$owner_name', '$email', '$password', '$phone', '$address', '$pincode')");

        if ($insert) {
            unset($_SESSION['otp']);
            unset($_SESSION['temp_seller']);
            $show_success = true;
        } else {
            $error = "Failed to register. Try again.";
        }
    } else {
        $error = "Incorrect OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Seller OTP</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

<div class="flex justify-center items-center w-auto h-[1.2cm] bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200">
    <h1 class="text-5xl font-bold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
        Seller OTP Verification
    </h1>
</div>
<div class="bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 flex justify-center items-start pt-25 h-screen">
    <?php if ($show_success): ?>
        <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-8 w-[400px] bg-gradient-to-r from-orange-300 via-pink-300 to-blue-300">
            <img src="images/tick.png" alt="Success" class="w-24 h-24 mb-4">
            <h2 class="text-3xl font-semibold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text mb-2">Seller Registered!</h2>
            <a href="login.php" class="mt-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-semibold py-2 px-4 rounded transition">
                Go to Login
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white p-8 rounded-xl shadow-lg w-[400px] text-center bg-gradient-to-r from-orange-300 via-pink-300 to-blue-300">
            <h2 class="text-3xl font-bold mb-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
                Verify OTP
            </h2>

            <?php if (!empty($error)): ?>
                <p class="text-red-600 font-semibold mb-3"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" class="w-full border border-gray-300 rounded p-2 mb-4 text-black" required />
                <button type="submit" class="w-full mt-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90">Verify</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
