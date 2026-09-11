<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Transaksi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="form-box">

        <h1>Tambah Transaksi</h1>

        <form action="simpan.php" method="POST">

            <!-- Nama Transaksi -->
            <div class="form-group">

                <label>
                    Nama Transaksi
                </label>

                <input
                    type="text"
                    name="nama_transaksi"
                    placeholder="Contoh: Gaji Bulanan"
                    required
                >

            </div>


            <!-- Jenis Transaksi -->
            <div class="form-group">

                <label>
                    Jenis Transaksi
                </label>

                <select name="jenis" required>

                    <option value="">
                        -- Pilih Jenis --
                    </option>

                    <option value="masuk">
                        Masuk
                    </option>

                    <option value="keluar">
                        Keluar
                    </option>

                </select>

            </div>


            <!-- Nominal -->
            <div class="form-group">

                <label>
                    Nominal
                </label>

                <input
                    type="number"
                    name="nominal"
                    placeholder="Contoh: 500000"
                    min="1"
                    required
                >

            </div>


            <!-- Tanggal -->
            <div class="form-group">

                <label>
                    Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    required
                >

            </div>


            <!-- Tombol -->
            <div class="button-group">

                <button type="submit" class="btn btn-primary">
                    Simpan Transaksi
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>