<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered. Try logging in.');window.location='customer_register.php';</script>";
        exit;
    }

    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['temp_customer'] = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'pincode' => $pincode
    ];

    $to = $email;
    $subject = "SnapKart Email Verification OTP";
    $message = "Your OTP for SnapKart registration is: $otp";
    $headers = "From: snapkart@localhost";

    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('OTP sent to your email.'); window.location='verify_customer_otp.php';</script>";
    } else {
        echo "<script>alert('Failed to send OTP. Check your XAMPP mail setup.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - SnapKart</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

    <div class="flex justify-center items-center w-auto h-[1.2cm] bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-transparent bg-clip-text">
            Register on SnapKart
        </h1>
    </div>

    <div class="flex justify-center items-start pt-14 bg-gradient-to-r from-orange-100 via-pink-300 to-purple-200 min-h-screen">
        <div class="flex flex-col h-auto w-[14cm] p-6 bg-gradient-to-r from-orange-300 via-pink-300 to-blue-300 rounded-2xl shadow-2xl border border-gray-300">
            
            <form method="POST" class="space-y-3">

                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        Full Name:
                    </label>
                    <input type="text" name="name" required placeholder="Enter your name"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        Email:
                    </label>
                    <input type="email" name="email" required placeholder="Enter your email"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        Password:
                    </label>
                    <input type="password" name="password" required placeholder="Enter password"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        Address:
                    </label>
                    <textarea name="address" required placeholder="Enter your address"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500"></textarea>
                </div>

                <div>
                    <label class="font-semibold bg-gradient-to-r from-orange-500 via-pink-600 to-blue-600 text-transparent bg-clip-text">
                        Pincode:
                    </label>
                    <input type="text" name="pincode" required placeholder="Enter your pincode"
                        class="w-full border border-gray-400 rounded p-2 text-black placeholder-gray-500" />
                </div>

                <button type="submit" class="w-full mt-4 bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 text-white font-bold p-2 rounded hover:opacity-90">
                    Register as Customer
                </button>
            </form>
        </div>
    </div>
</body>
</html>
