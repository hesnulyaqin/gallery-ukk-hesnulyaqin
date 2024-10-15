<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data user berdasarkan username
    $query = "SELECT * FROM user WHERE Username = '$username'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['Password'])) {
            // Jika password cocok, buat session
            $_SESSION['UserID'] = $row['UserID']; // Ganti 'id' sesuai dengan nama kolom id di tabel user
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role'] = $row['role'];

            // Redirect ke halaman yang sesuai berdasarkan role
            if ($row['role'] === 'admin') {
                header('Location: dataalbum.php'); // Sesuaikan halaman admin
            } else {
                header('Location: beranda.php'); // Sesuaikan halaman user
            }
            exit;
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Login</title>
</head>

<body class="bg-gray-200">
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white p-6 shadow-lg rounded-lg">
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 ">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto"
                    src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
                <h2 class="mt-10 mb-9 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to
                    your
                    account</h2>
            </div>

            <form class="space-y-6" action="" method="post">
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                    <div class="mt-2">
                        <input id="username" name="username" type="text" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                        in</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Belum Punya Akun ?
                <a href="register.php" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Register
                    Disini!</a>
            </p>
        </div>
    </div>
</body>

</html>