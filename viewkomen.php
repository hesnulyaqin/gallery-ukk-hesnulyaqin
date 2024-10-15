<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi.php sudah ada dan benar

// Cek apakah ada ID foto yang diberikan di URL
if (isset($_GET['id'])) {
    $foto_id = $_GET['id'];

    // Ambil data foto berdasarkan FotoID
    $sql = "SELECT f.FotoID, f.LokasiFile, f.JudulFoto, u.Username, f.TanggalUnggah 
            FROM foto f 
            JOIN user u ON f.UserID = u.UserID 
            WHERE f.FotoID = '$foto_id'";
    $result = $koneksi->query($sql);

    // Cek apakah foto ditemukan
    if ($result->num_rows > 0) {
        $foto = $result->fetch_assoc(); // Ambil data foto
    } else {
        echo "Foto tidak ditemukan.";
        exit;
    }
} else {
    echo "ID foto tidak diberikan.";
    exit;
}

// Menangani pengisian komentar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['UserID'])) {
        echo "Anda harus login untuk mengirim komentar.";
    } else {
        $FotoID = $foto_id; // Ambil FotoID dari foto yang ditampilkan
        $userID = $_SESSION['UserID']; // Ambil ID pengguna dari sesi
        $isiKomentar = $_POST['isiKomentar'];
        $tanggalKomentar = date('Y-m-d H:i:s'); // Menambahkan waktu ke tanggal

        // Cek apakah FotoID valid
        $sqlCheck = "SELECT * FROM foto WHERE FotoID = '$FotoID'";
        $resultCheck = $koneksi->query($sqlCheck);

        if ($resultCheck->num_rows > 0) {
            // Masukkan komentar ke dalam tabel
            $sql = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$FotoID', '$userID', '$isiKomentar', '$tanggalKomentar')";
            if ($koneksi->query($sql) === TRUE) {
                echo "<script>alert('Komentar berhasil ditambahkan.');</script>";
            } else {
                echo "<script>alert('Error: " . $koneksi->error . "');</script>";
            }
        } else {
            echo "<script>alert('FotoID tidak valid.');</script>";
        }
    }
}

// Mengambil komentar dari database
$sql = "SELECT k.IsiKomentar, k.TanggalKomentar, u.Username 
        FROM komentarfoto k 
        JOIN user u ON k.UserID = u.UserID 
        WHERE k.FotoID = '$foto_id' 
        ORDER BY k.TanggalKomentar DESC";
$resultKomentar = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin-top: 30px;
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .card-img-top {
        border-bottom: 1px solid #eee;
    }

    .card-body {
        padding: 2rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .text-muted {
        color: #6c757d;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        border-radius: 50px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
        transform: translateY(-2px);
    }

    .comment-section {
        margin-top: 2rem;
    }

    .comment {
        background-color: #fff;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .comment-username {
        font-weight: 600;
        color: #007bff;
    }

    .comment-date {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .comment-content {
        margin-bottom: 0;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.75rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    </style>
</head>

<body>
    <div class="container">
        <a href="javascript:history.back()" class="btn btn-outline-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        <div class="card mb-4">
            <img src="<?= $foto['LokasiFile'] ?>" alt="<?= $foto['JudulFoto'] ?>" class="card-img-top">
            <div class="card-body">
                <h1 class="card-title"><?= htmlspecialchars($foto['JudulFoto']) ?></h1>
                <p class="text-muted mb-2">
                    <i class="fas fa-user me-2"></i>Diunggah oleh:
                    <strong><?= htmlspecialchars($foto['Username']) ?></strong>
                </p>
                <p class="text-muted mb-3">
                    <i class="fas fa-calendar-alt me-2"></i>Tanggal Unggah:
                    <strong><?= date('d M Y', strtotime($foto['TanggalUnggah'])) ?></strong>
                </p>
                <a href='download.php?image=<?= urlencode(basename($foto['LokasiFile'])) ?>'
                    class='action-button cetak-button'>
                    <i class="fas fa-download me-2"></i>Unduh Foto
                </a>

            </div>
        </div>

        <div class="comment-section">
            <h2 class="mb-3">Komentar</h2>

            <?php if ($resultKomentar->num_rows > 0): ?>
            <?php while ($row = $resultKomentar->fetch_assoc()): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-username"><?php echo htmlspecialchars($row['Username']); ?></span>
                    <span class="comment-date"><?php echo htmlspecialchars($row['TanggalKomentar']); ?></span>
                </div>
                <p class="comment-content"><?php echo nl2br(htmlspecialchars($row['IsiKomentar'])); ?></p>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-muted">Belum ada komentar.</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['UserID'])): ?>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <textarea name="isiKomentar" required placeholder="Tulis komentar..." class="form-control"
                        rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                </button>
            </form>
            <?php else: ?>
            <p class="mt-4">Anda harus <a href="login.php" class="text-primary">login</a> untuk mengirim komentar.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$koneksi->close(); // Tutup koneksi
?>