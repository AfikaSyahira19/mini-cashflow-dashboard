<?php

require "koneksi.php";


// ================================
// 1. AMBIL DATA DARI FORM
// ================================

$nama_transaksi = trim($_POST['nama_transaksi'] ?? '');

$jenis = $_POST['jenis'] ?? '';

$nominal = $_POST['nominal'] ?? '';

$tanggal = $_POST['tanggal'] ?? '';


// ================================
// 2. VALIDASI FIELD KOSONG
// ================================

if (
    $nama_transaksi == '' ||
    $jenis == '' ||
    $nominal == '' ||
    $tanggal == ''
) {

    die("Semua field wajib diisi.");

}


// ================================
// 3. VALIDASI NOMINAL
// ================================

if ($nominal <= 0) {

    die("Nominal harus lebih dari 0.");

}


// ================================
// 4. VALIDASI JENIS
// ================================

if (
    $jenis != 'masuk' &&
    $jenis != 'keluar'
) {

    die("Jenis transaksi tidak valid.");

}


// ================================
// 5. INSERT KE DATABASE
// ================================

$sql = "
    INSERT INTO transaksi
    (
        nama_transaksi,
        jenis,
        nominal,
        tanggal
    )
    VALUES
    (
        :nama_transaksi,
        :jenis,
        :nominal,
        :tanggal
    )
";


$stmt = $pdo->prepare($sql);


// ================================
// 6. EKSEKUSI QUERY
// ================================

$stmt->execute([

    ':nama_transaksi' => $nama_transaksi,

    ':jenis' => $jenis,

    ':nominal' => $nominal,

    ':tanggal' => $tanggal

]);


// ================================
// 7. KEMBALI KE DASHBOARD
// ================================

header("Location: index.php");

exit;