<?php
session_start(); // Memulai session
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $namalengkap = $_POST['namalengkap'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    // Cek apakah username sudah digunakan
    $checkUsername = "SELECT * FROM user WHERE Username = '$username'";
    $resultUsername = $koneksi->query($checkUsername);

    // Cek apakah email sudah digunakan
    $checkEmail = "SELECT * FROM user WHERE Email = '$email'";
    $resultEmail = $koneksi->query($checkEmail);

    if ($resultUsername->num_rows > 0) {
        $_SESSION['message'] = "Username sudah digunakan!";
    } elseif ($resultEmail->num_rows > 0) {
        $_SESSION['message'] = "Email sudah digunakan!";
    } else {
        // Jika username dan email belum digunakan, lakukan insert data
        $query = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Role) 
                  VALUES ('$username', '$password', '$email', '$namalengkap', '$alamat', '$role')";

        if ($koneksi->query($query) === true) {
            $_SESSION['message'] = "Register berhasil";
            header('location: login.php');
            exit();
        } else {
            $_SESSION['message'] = "Gagal: " . $koneksi->error;
        }
    }
}

// Menampilkan pesan jika ada
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10 mt-10 mb-16">
            <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                <img class="mx-auto h-10 w-auto"
                    src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            </div>

            <!-- Memindahkan teks 'Create your account' ke dalam block putih -->
            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create your account</h2>

            <!-- Pesan Notifikasi -->
            <?php if (!empty($message)): ?>
            <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md bg-red-100 text-red-700 p-4 rounded">
                <?= $message ?>
            </div>
            <?php endif; ?>

            <form class="space-y-6 mt-6" action="" method="post">
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                    <div class="mt-2">
                        <input id="username" name="username" type="text" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="namalengkap" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        Lengkap</label>
                    <div class="mt-2">
                        <input id="namalengkap" name="namalengkap" type="text" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <input id="alamat" name="alamat" type="text" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Field role disembunyikan -->
                <div class="hidden">
                    <select name="role" required>
                        <option value="user">User</option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Simpan</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="login.php" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login
                    disini</a>
            </p>
        </div>
    </div>
</body>

</html>