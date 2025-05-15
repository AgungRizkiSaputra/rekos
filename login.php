<?php
session_start();
include 'includes/config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Login sukses, simpan session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah";
            }
        } else {
            $error = "Email tidak ditemukan";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clean Login</title>
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
            <img src="images/login.png" alt="Login Illustration" class="h-full w-full object-cover" />
        </div>

        <!-- Right Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Login 👋</h2>
            <p class="text-gray-500 mb-8">Sign in to continue to your dashboard</p>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form class="space-y-6" action="" method="POST">
                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" required
                        value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="••••••••" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600">
                    <label class="flex items-center">
                        <input type="checkbox" class="mr-2 rounded border-gray-300" /> Remember me
                    </label>
                    <a href="#" class="hover:underline text-indigo-500">Forgot password?</a>
                </div>

                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg transition duration-300 font-semibold">
                    Login
                </button>
            </form>

            <p class="text-sm text-center text-gray-600 mt-6">
                Don’t have an account?
                <a href="register.php" class="text-indigo-600 font-semibold hover:underline">Sign Up</a>
            </p>
        </div>
    </div>

</body>

</html>