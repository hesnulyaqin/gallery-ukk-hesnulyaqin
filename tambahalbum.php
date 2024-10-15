<?php
session_start();
require 'koneksi.php'; // Pastikan kamu sudah membuat file koneksi.php

// Cek apakah pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Menangkap nilai parameter redirect jika ada
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dataalbum'; // Default ke dataalbum jika tidak ada parameter

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaAlbum = $_POST['nama_album'];
    $deskripsi = $_POST['deskripsi'];
    $tanggalDibuat = date('Y-m-d'); // Menggunakan tanggal hari ini
    $userID = $_SESSION['UserID']; // Ambil UserID dari session

    // Query untuk menambahkan album
    $sql = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES (?, ?, ?, ?)";

    // Persiapkan dan eksekusi query
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param('sssi', $namaAlbum, $deskripsi, $tanggalDibuat, $userID);

        if ($stmt->execute()) {
            // Redirect ke halaman yang sesuai berdasarkan nilai redirect
            header("Location: $redirect.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $koneksi->error;
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Album</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h2 {
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button {
        background-color: #4CAF50;
        /* Hijau */
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
        /* Hijau gelap */
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        color: white;
        background-color: #007bff;
        /* Biru */
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .back-link:hover {
        background-color: #0056b3;
        /* Biru gelap */
    }
    </style>
</head>

<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h2>Tambah Album</h2>
        <form method="post" action="">
            <label for="nama_album">Nama Album:</label>
            <input type="text" name="nama_album" id="nama_album" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" required></textarea>

            <input type="hidden" id="UserID" name="UserID" value="<?= $_SESSION['UserID'] ?>" required>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>

</html>