<?php
include '../config/database.php';
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
}
$id_barang = $_GET['id'];
$query = "DELETE FROM barang WHERE id_barang = $id_barang";
mysqli_query($conn, $query);
header("Location: barang_lihat.php");
