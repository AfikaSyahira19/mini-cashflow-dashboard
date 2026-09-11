<?php

require "koneksi.php";


// ==================================================
// 1. MENGHITUNG TOTAL UANG MASUK
// ==================================================

$sqlMasuk = "
    SELECT SUM(nominal)
    FROM transaksi
    WHERE jenis = 'masuk'
";

$stmtMasuk = $pdo->query($sqlMasuk);

$totalMasuk = $stmtMasuk->fetchColumn();


// Jika belum ada transaksi, ubah menjadi 0
if ($totalMasuk == null) {

    $totalMasuk = 0;

}


// ==================================================
// 2. MENGHITUNG TOTAL UANG KELUAR
// ==================================================

$sqlKeluar = "
    SELECT SUM(nominal)
    FROM transaksi
    WHERE jenis = 'keluar'
";

$stmtKeluar = $pdo->query($sqlKeluar);

$totalKeluar = $stmtKeluar->fetchColumn();


if ($totalKeluar == null) {

    $totalKeluar = 0;

}


// ==================================================
// 3. MENGHITUNG SALDO
// ==================================================

$saldo = $totalMasuk - $totalKeluar;


// ==================================================
// 4. MENGAMBIL DATA FILTER
// ==================================================

$bulan = $_GET['bulan'] ?? '';

$tahun = $_GET['tahun'] ?? '';


// ==================================================
// 5. QUERY DATA TRANSAKSI
// ==================================================

$sql = "
    SELECT *
    FROM transaksi
    WHERE 1=1
";

$params = [];


// ==================================================
// 6. FILTER BULAN
// ==================================================

if ($bulan != '') {

    $sql .= "
        AND MONTH(tanggal) = :bulan
    ";

    $params[':bulan'] = $bulan;

}


// ==================================================
// 7. FILTER TAHUN
// ==================================================

if ($tahun != '') {

    $sql .= "
        AND YEAR(tanggal) = :tahun
    ";

    $params[':tahun'] = $tahun;

}


// ==================================================
// 8. URUTKAN DATA
// ==================================================

$sql .= "
    ORDER BY tanggal DESC, id DESC
";


// ==================================================
// 9. JALANKAN QUERY
// ==================================================

$stmt = $pdo->prepare($sql);

$stmt->execute($params);


// Ambil semua data
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================================
// 10. FUNCTION RUPIAH
// ==================================================

function rupiah($nominal)
{

    return "Rp " . number_format(
        $nominal,
        0,
        ',',
        '.'
    );

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Mini Cashflow Dashboard
    </title>

    <link rel="stylesheet"
          href="style.css">

</head>


<body>


<div class="container">


    <!-- ========================================= -->
    <!-- HEADER -->
    <!-- ========================================= -->

    <div class="header">

        <div>

            <h1>
                Mini Cashflow
            </h1>

            <p>
                Dashboard pencatatan arus kas pribadi
            </p>

        </div>


        <a href="tambah.php"
           class="btn btn-primary">

            + Tambah Transaksi

        </a>

    </div>



    <!-- ========================================= -->
    <!-- CARD RINGKASAN -->
    <!-- ========================================= -->

    <div class="cards">


        <!-- TOTAL MASUK -->

        <div class="card card-masuk">

            <p>
                Total Masuk
            </p>

            <h2>

                <?= rupiah($totalMasuk); ?>

            </h2>

        </div>



        <!-- TOTAL KELUAR -->

        <div class="card card-keluar">

            <p>
                Total Keluar
            </p>

            <h2>

                <?= rupiah($totalKeluar); ?>

            </h2>

        </div>



        <!-- SALDO -->

        <div class="card card-saldo">

            <p>
                Saldo Akhir
            </p>

            <h2>

                <?= rupiah($saldo); ?>

            </h2>

        </div>


    </div>



    <!-- ========================================= -->
    <!-- FILTER -->
    <!-- ========================================= -->

    <div class="filter-box">

        <h3>
            Filter Riwayat Transaksi
        </h3>


        <form method="GET"
              class="filter-form">


            <!-- BULAN -->

            <div>

                <label>
                    Bulan
                </label>

                <select name="bulan">

                    <option value="">
                        Semua Bulan
                    </option>

                    <option value="1"
                        <?= $bulan == '1' ? 'selected' : '' ?>>
                        Januari
                    </option>

                    <option value="2"
                        <?= $bulan == '2' ? 'selected' : '' ?>>
                        Februari
                    </option>

                    <option value="3"
                        <?= $bulan == '3' ? 'selected' : '' ?>>
                        Maret
                    </option>

                    <option value="4"
                        <?= $bulan == '4' ? 'selected' : '' ?>>
                        April
                    </option>

                    <option value="5"
                        <?= $bulan == '5' ? 'selected' : '' ?>>
                        Mei
                    </option>

                    <option value="6"
                        <?= $bulan == '6' ? 'selected' : '' ?>>
                        Juni
                    </option>

                    <option value="7"
                        <?= $bulan == '7' ? 'selected' : '' ?>>
                        Juli
                    </option>

                    <option value="8"
                        <?= $bulan == '8' ? 'selected' : '' ?>>
                        Agustus
                    </option>

                    <option value="9"
                        <?= $bulan == '9' ? 'selected' : '' ?>>
                        September
                    </option>

                    <option value="10"
                        <?= $bulan == '10' ? 'selected' : '' ?>>
                        Oktober
                    </option>

                    <option value="11"
                        <?= $bulan == '11' ? 'selected' : '' ?>>
                        November
                    </option>

                    <option value="12"
                        <?= $bulan == '12' ? 'selected' : '' ?>>
                        Desember
                    </option>

                </select>

            </div>



            <!-- TAHUN -->

            <div>

                <label>
                    Tahun
                </label>

                <input
                    type="number"
                    name="tahun"
                    placeholder="2026"
                    value="<?= htmlspecialchars($tahun); ?>"
                >

            </div>



            <!-- TOMBOL -->

            <div class="filter-buttons">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Filter

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary">

                    Reset

                </a>

            </div>


        </form>

    </div>



    <!-- ========================================= -->
    <!-- TABEL TRANSAKSI -->
    <!-- ========================================= -->

    <div class="table-box">

        <h3>
            Riwayat Transaksi
        </h3>


        <div class="table-responsive">

            <table>


                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Nama Transaksi
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Nominal
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if (count($transaksi) > 0): ?>


                    <?php foreach (
                        $transaksi as $index => $row
                    ): ?>


                        <tr class="<?= $row['jenis']; ?>">


                            <td>

                                <?= $index + 1; ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $row['nama_transaksi']
                                ); ?>

                            </td>


                            <td>

                                <?php if (
                                    $row['jenis'] == 'masuk'
                                ): ?>

                                    <span class="badge badge-masuk">
                                        Masuk
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-keluar">
                                        Keluar
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?= rupiah(
                                    $row['nominal']
                                ); ?>

                            </td>


                            <td>

                                <?= date(
                                    'd-m-Y',
                                    strtotime(
                                        $row['tanggal']
                                    )
                                ); ?>

                            </td>


                            <td>

                                <a
                                    href="hapus.php?id=<?= $row['id']; ?>"
                                    class="btn-delete"
                                    onclick="return confirm(
                                        'Yakin ingin menghapus transaksi ini?'
                                    );"
                                >

                                    Hapus

                                </a>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="6"
                            class="empty">

                            Belum ada transaksi.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>

        </div>

    </div>


</div>

</body>

</html>