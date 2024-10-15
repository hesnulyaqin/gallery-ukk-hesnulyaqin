<?php
include '../koneksi.php';

// Mengambil parameter redirect dari URL
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'read_foto.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM foto WHERE FotoID = $id";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $judul_foto = $_POST['judul_foto'];
    $deskripsi_foto = $_POST['deskripsi_foto'];
    $query_update = "UPDATE foto SET JudulFoto = '$judul_foto', DeskripsiFoto = '$deskripsi_foto' WHERE FotoID = $id";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: $redirect"); // Menggunakan nilai redirect untuk pengalihan
        exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
    } else {
        echo "Error: " . $query_update . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f9fc;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        margin-bottom: 20px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #5a6268;
    }
    </style>
</head>

<body>

    <a href="javascript:history.back()" class="back-button">Kembali</a>

    <h2>Edit Foto</h2>
    <form action="" method="POST">
        <label>Judul Foto:</label>
        <input type="text" name="judul_foto" value="<?= htmlspecialchars($data['JudulFoto']) ?>" required>

        <label>Deskripsi Foto:</label>
        <textarea name="deskripsi_foto" required><?= htmlspecialchars($data['DeskripsiFoto']) ?></textarea>

        <button type="submit" name="submit">Update Foto</button>
    </form>
</body>

</html>