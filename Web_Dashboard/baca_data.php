<?php
include 'koneksi.php';

// Ambil data terakhir berdasarkan kolom 'waktu' atau 'id'
$sql = "SELECT * FROM tb_jantung ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    // Data default jika kosong
    $data = ["bpm" => 0, "rpe" => "-", "keterangan" => "Menunggu Data..."];
}

echo json_encode($data);
$conn->close();
?>
