<?php
session_start();
include 'includes/config.php';

$error = "";
$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama!";
    } elseif (empty($name) || empty($email) || empty($password)) {
        $error = "Semua field harus diisi";
    } else {
        // Cek email sudah ada atau belum
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah terdaftar";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
                exit;
            } else {
                $error = "Terjadi kesalahan saat registrasi";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        fadeIn: 'fadeIn 0.8s ease-out both',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: 0,
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: 1,
                                transform: 'translateY(0)'
                            },
                        },
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div
        class="flex flex-col md:flex-row bg-white rounded-3xl shadow-lg overflow-hidden w-full max-w-5xl animate-fadeIn">
        <!-- Left Image -->
        <div class="hidden md:block md:w-1/2">
            <img src="images/login.png" alt="Sign Up Illustration" class="h-full w-full object-cover" />
        </div>

        <!-- Right Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Create an Account 🚀</h2>
            <p class="text-gray-500 mb-8">Join us and manage your dashboard smarter</p>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form class="space-y-6" action="" method="POST">
                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="name">Full Name</label>
                    <input id="name" name="name" type="text" placeholder="Your Name" required
                        value="<?= htmlspecialchars($name) ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" required
                        value="<?= htmlspecialchars($email) ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="••••••••" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" placeholder="••••••••" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg transition duration-300 font-semibold">
                    Sign Up
                </button>
            </form>

            <p class="text-sm text-center text-gray-600 mt-6">
                Already have an account?
                <a href="login.php" class="text-indigo-600 font-semibold hover:underline">Login</a>
            </p>
        </div>
    </div>

    <script>
        // Optional client-side validation for password match
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            const pwd = document.getElementById('password').value;
            const cpwd = document.getElementById('confirm_password').value;
            if (pwd !== cpwd) {
                e.preventDefault();
                alert('Password and Confirm Password do not match!');
            }
        });
    </script>

</body>

</html>