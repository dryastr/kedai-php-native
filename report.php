<?php
include "proses/connect.php";
date_default_timezone_set('Asia/Jakarta');

$totalPemasukan = 0;
$result = [];
$startDate = '';
$endDate = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $dateFilter = "DATE(tb_order.waktu_order) BETWEEN '$startDate' AND '$endDate'";

    $queryTotal = mysqli_query($conn, "SELECT SUM(total_bayar) AS total_bayar
                                       FROM tb_bayar
                                       JOIN tb_order ON tb_bayar.id_bayar = tb_order.id_order
                                       WHERE $dateFilter");

    $totalResult = mysqli_fetch_assoc($queryTotal);
    $totalPemasukan = $totalResult['total_bayar'];

    $queryDetail = mysqli_query($conn, "SELECT tb_order.*, tb_bayar.*, nama, SUM(harga*jumlah) AS harganya
                                        FROM tb_order
                                        LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
                                        LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
                                        LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
                                        JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
                                        WHERE $dateFilter
                                        GROUP BY tb_order.id_order
                                        ORDER BY tb_order.waktu_order ASC");

    $result = mysqli_fetch_all($queryDetail, MYSQLI_ASSOC);
} else {
    $queryDetail = mysqli_query($conn, "SELECT tb_order.*, tb_bayar.*, nama, SUM(harga*jumlah) AS harganya
                                        FROM tb_order
                                        LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
                                        LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
                                        LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
                                        JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
                                        GROUP BY tb_order.id_order
                                        ORDER BY tb_order.waktu_order ASC");

    $result = mysqli_fetch_all($queryDetail, MYSQLI_ASSOC);
}
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Page View Item
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <div class="d-flex mt-2">
                <?php if (!empty($startDate) && !empty($endDate)) { ?>
                    <div class="mr-2">
                        <form method="POST" action="generate_pdf.php" target="_blank">
                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                            <button type="submit" class="btn btn-secondary">Cetak PDF</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="mb-3" style="margin-left: 5px;">
                        <form method="POST" action="generate_all_pdf.php" target="_blank" class="ml-5">
                            <button type="submit" class="btn btn-secondary ml-2">Cetak Semua Data PDF</button>
                        </form>
                    </div>
                <?php } ?>
            </div>


            <?php
            if (empty($result)) {
                echo "Data Order tidak ditemukan";
            } else {
                if (!empty($startDate) && !empty($endDate)) {
                    echo "<div class='jumlah-total-bayar my-4'>Total Pemasukan dari $startDate sampai $endDate: " . number_format($totalPemasukan, 0, ',', '.') . "</div>";
                }
            ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="example">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col">No</th>
                                <th scope="col">Kode Order</th>
                                <th scope="col">Waktu Order</th>
                                <th scope="col">Waktu Bayar</th>
                                <th scope="col">Pelanggan</th>
                                <th scope="col">Tempat</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Pelayan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($result as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++ ?></th>
                                    <td><?php echo $row['id_order'] ?></td>
                                    <td><?php echo $row['waktu_order'] ?></td>
                                    <td><?php echo $row['waktu_bayar'] ?></td>
                                    <td><?php echo $row['pelanggan'] ?></td>
                                    <td><?php echo $row['tempat'] ?></td>
                                    <td><?php echo number_format($row['harganya'], 0, ',', '.') ?></td>
                                    <td><?php echo $row['nama'] ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="btn btn-info btn-sm me-1" href="./?x=viewitem&order=<?php echo $row['id_order'] . "&tempat=" . $row['tempat'] . "&pelanggan=" . $row['pelanggan'] ?>"><i class="bi bi-eye-fill"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>