<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_kesehatan"; // Sesuai foto Anda

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>