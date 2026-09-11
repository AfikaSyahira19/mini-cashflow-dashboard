<?php
/**
 * hapus.php
 * -----------------------------------------------------------
 * Menghapus satu baris transaksi. Hanya menerima request
 * method POST (bukan link GET biasa) supaya penghapusan tidak
 * bisa dipicu cuma dengan mengunjungi URL. Konfirmasi visual
 * (confirm()) dilakukan di sisi JavaScript pada index.php
 * sebelum form ini disubmit.
 * -----------------------------------------------------------
 */

session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$idRaw = $_POST['id'] ?? null;
$id    = is_scalar($idRaw) ? filter_var($idRaw, FILTER_VALIDATE_INT) : false;

$bulan = (isset($_POST['bulan']) && is_string($_POST['bulan'])) ? trim($_POST['bulan']) : '';
$tahun = (isset($_POST['tahun']) && is_string($_POST['tahun'])) ? trim($_POST['tahun']) : '';

if ($id !== false && $id > 0) {
    $stmt = $pdo->prepare('DELETE FROM mini_cashflow WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Transaksi berhasil dihapus.',
        ];
    } else {
        // Bisa terjadi kalau data sudah dihapus lebih dulu (mis. tombol diklik dua kali)
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => 'Transaksi tidak ditemukan, mungkin sudah dihapus sebelumnya.',
        ];
    }
} else {
    $_SESSION['flash'] = [
        'type'    => 'error',
        'message' => 'ID transaksi tidak valid, data gagal dihapus.',
    ];
}

// Kembali ke index.php sambil mempertahankan filter bulan/tahun yang aktif
$query = [];
if ($bulan !== '' && ctype_digit($bulan)) {
    $query['bulan'] = $bulan;
}
if ($tahun !== '' && ctype_digit($tahun)) {
    $query['tahun'] = $tahun;
}
$queryString = http_build_query($query);

header('Location: index.php' . ($queryString !== '' ? '?' . $queryString : ''));
exit;