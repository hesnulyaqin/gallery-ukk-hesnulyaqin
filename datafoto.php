<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi ke database sudah benar

// Cek apakah pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Inisialisasi variabel pencarian
$searchTerm = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Query untuk mengambil data foto dengan filter pencarian
$sql = "SELECT * FROM foto WHERE 
        JudulFoto LIKE '%$searchTerm%' OR 
        FotoID LIKE '%$searchTerm%' OR 
        DeskripsiFoto LIKE '%$searchTerm%'"; // Ambil data dari tabel foto
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilkan Foto</title>
    <link rel="stylesheet" href="style.css"> <!-- Tambahkan file CSS jika perlu -->
    <style>
    .data {
        position: relative;
        right: 5%;
        bottom: -100px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        text-align: left;
        padding: 10px;
        border: 1px solid #ddd;
    }

    img {
        width: 100px;
        height: auto;
    }

    .print-button {
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }
    </style>
    <script>
    function printRow(rowId) {
        var row = document.getElementById(rowId);
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Row</title>');
        printWindow.document.write(
            '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>'
        );
        printWindow.document.write('</head><body>');
        printWindow.document.write('<table>');

        // Print header
        printWindow.document.write('<tr>');
        var headers = row.closest('table').querySelectorAll('th');
        headers.forEach(function(header) {
            printWindow.document.write('<th>' + header.innerText + '</th>');
        });
        printWindow.document.write('</tr>');

        // Print row data
        printWindow.document.write('<tr>');
        var cells = row.querySelectorAll('td');
        cells.forEach(function(cell, index) {
            if (index === 6) { // Image column
                printWindow.document.write('<td><img src="' + cell.querySelector('img').src +
                    '" style="width:100px;height:auto;"></td>');
            } else {
                printWindow.document.write('<td>' + cell.innerHTML + '</td>');
            }
        });
        printWindow.document.write('</tr>');

        printWindow.document.write('</table>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    function printImage(imageSrc) {
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Cetak Gambar</title></head><body style="margin:0;">');
        printWindow.document.write('<img src="' + imageSrc + '" style="width:100vw; height:auto; object-fit:cover;">');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
    </script>
</head>

<body>

    <?php
    require 'dashboard.php'; // Mengambil dashboard
    ?>
    <div class="data">
        <h2>Daftar Foto</h2>

        <!-- Form Pencarian -->
        <form method="POST" action="">
            <input type="text" name="search_term" placeholder="Cari Judul, ID, atau Deskripsi"
                value="<?= htmlspecialchars($searchTerm) ?>">
            <input type="submit" value="Cari">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Foto ID</th>
                    <th>Judul Foto</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Unggah</th>
                    <th>Album ID</th>
                    <th>User ID</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $rowId = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='row-" . $rowId . "'>";
                        echo "<td>" . $row['FotoID'] . "</td>";
                        echo "<td>" . $row['JudulFoto'] . "</td>";
                        echo "<td>" . $row['DeskripsiFoto'] . "</td>";
                        echo "<td>" . $row['TanggalUnggah'] . "</td>";
                        echo "<td>" . $row['AlbumID'] . "</td>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td><img src='" . $row['LokasiFile'] . "' alt='" . $row['JudulFoto'] . "'></td>";
                        echo "<td>
                                <a href='viewkomen.php?id=" . $row['FotoID'] . "'>View</a> |
                                <a href='crudfoto/edit.php?id=" . $row['FotoID'] . "&redirect=" . $_SERVER['PHP_SELF'] . "' class='edit-button bg-red-500 text-white px-2 py-1 rounded'>Edit</a> |
                                <a href='crudfoto/hapus.php?id=" . $row['FotoID'] . "' onclick='return confirm(\"Anda yakin ingin menghapus foto ini?\")'>Hapus</a> |
                                <a href='download.php?image=" . urlencode(basename($row['LokasiFile'])) . "' class='action-button cetak-button'>Unduh</a> |
                                <span class='print-button' onclick=\"printRow('row-" . $rowId . "')\">Print Row</span>
                              </td>";
                        echo "</tr>";
                        $rowId++;
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada foto yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="tambahfoto.php?redirect_id=datafoto.php">Tambah Foto</a>
    </div>
</body>

</html>

<?php
$koneksi->close();
?>