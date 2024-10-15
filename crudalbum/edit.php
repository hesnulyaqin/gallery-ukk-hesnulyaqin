<?php
include '../koneksi.php';

$id = $_GET['id'];
$sql = "SELECT * FROM album WHERE AlbumID = $id";
$result = $koneksi->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaAlbum = $_POST['nama_album'];
    $deskripsi = $_POST['deskripsi'];
    $userID = $_POST['user_id'];

    $sql = "UPDATE album SET NamaAlbum = '$namaAlbum', Deskripsi = '$deskripsi', UserID = '$userID' WHERE AlbumID = $id";

    if ($koneksi->query($sql) === TRUE) {
        echo "Album berhasil diupdate";
        $redirect = $_GET['redirect'];
        header("Location: ../$redirect.php"); // Gunakan string interpolasi
        exit; // Tambahkan exit setelah header untuk menghentikan skrip
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }

    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f9fc;
        margin: 0;
        padding: 20px;
    }

    h1 {
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
        display: block;
        margin-bottom: 8px;
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

    input[type="number"] {
        display: none;
        /* Sembunyikan input untuk user_id */
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
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

    <h1>Edit Album</h1>
    <form method="POST" action="">
        <label for="nama_album">Nama Album:</label>
        <input type="text" name="nama_album" id="nama_album" value="<?php echo htmlspecialchars($row['NamaAlbum']); ?>"
            required>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" required><?php echo htmlspecialchars($row['Deskripsi']); ?></textarea>

        <input type="number" name="user_id" value="<?php echo $row['UserID']; ?>" hidden>

        <input type="submit" value="Update">
    </form>
</body>

</html>