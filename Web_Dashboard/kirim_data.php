<?php
include 'koneksi.php';

// Menerima data dari ESP32
if (isset($_POST['bpm']) && isset($_POST['rpe']) && isset($_POST['keterangan'])) {
    $bpm = $_POST['bpm'];
    $rpe = $_POST['rpe'];
    $ket = $_POST['keterangan']; // Mengambil data POST dengan key 'keterangan'

    // Masukkan ke tabel tb_jantung, kolom 'keterangan'
    $sql = "INSERT INTO tb_jantung (bpm, rpe, keterangan) VALUES ('$bpm', '$rpe', '$ket')";

    if ($conn->query($sql) === TRUE) {
        echo "Data Berhasil Disimpan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Tidak ada data diposting";
}
$conn->close();
?>
