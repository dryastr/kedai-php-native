<?php
include "proses/connect.php";

$query = mysqli_query($conn, "SELECT *, SUM(harga*jumlah) AS harganya, tb_order.waktu_order FROM tb_list_order
    LEFT JOIN tb_order ON tb_order.id_order = tb_list_order.kode_order
    LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
    LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
    GROUP BY id_list_order
    HAVING tb_list_order.kode_order = $_GET[order]");

$kode = $_GET['order'];
$tempat = $_GET['tempat'];
$pelanggan = $_GET['pelanggan'];

while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
    // $kode = $record['id_order'];
    // $tempat = $record['tempat'];
    // $pelanggan = $record['pelanggan'];
}

$select_menu = mysqli_query($conn, "SELECT id,nama_menu FROM tb_daftar_menu");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Page Item Order
        </div>
        <div class="card-body">
            <a href="order" class="btn btn-secondary mb-2"><i class="bi bi-skip-backward-fill"></i></a>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="kodeorder" value="<?php echo $kode; ?>">
                        <label for="kodeorder">Kode Order</label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="tempat" value="<?php echo $tempat; ?>">
                        <label for="tempat">Tempat</label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="pelanggan" value="<?php echo $pelanggan; ?>">
                        <label for="kodeorder">Pelanggan</label>
                    </div>
                </div>

                <!-- Modal Tambah Item -->
                <div class="modal fade" id="tambahItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Order</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate action="proses/proses_input_orderitem.php" method="POST">
                                    <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                    <input type="hidden" name="tempat" value="<?php echo $tempat ?>">
                                    <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" name="menu" id="">
                                                    <option selected hidden value="">Pilih Menu</option>
                                                    <?php
                                                    foreach ($select_menu as $value) {
                                                        echo "<option value=$value[id]>$value[nama_menu]</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label for="menu">Menu Makanan/Minuman/Snack</label>
                                                <div class="invalid-feedback">
                                                    Pilih Menu.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="floatingInput" placeholder="Jumlah Porsi" name="jumlah" required>
                                                <label for="floatingInput">Qty</label>
                                                <div class="invalid-feedback">
                                                    Masukkan jumlah porsi.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="Catatan" name="catatan">
                                                <label for="floatingPassword">Catatan</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="input_orderitem_validate" value="12345">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal Tambah Item -->

                <?php
                if (empty($result)) {
                    echo "Data Order tidak ditemukan";
                } else {
                    foreach ($result as $row) {
                ?>

                        <!-- Modal Edit-->
                        <div class="modal fade" id="ModalEdit<?php echo $row['id_list_order'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pesanan</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="needs-validation" novalidate action="proses/proses_edit_orderitem.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $row['id_list_order'] ?>">
                                            <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                            <input type="hidden" name="tempat" value="<?php echo $tempat ?>">
                                            <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="form-floating mb-3">
                                                        <select class="form-select" name="menu" id="">
                                                            <option selected hidden value="">Pilih Menu</option>
                                                            <?php
                                                            foreach ($select_menu as $value) {
                                                                if ($row['menu'] == $value['id']) {
                                                                    echo "<option selected value=$value[id]>$value[nama_menu]</option>";
                                                                } else {
                                                                    echo "<option value=$value[id]>$value[nama_menu]</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="menu">Menu Makanan/Minuman/Snack</label>
                                                        <div class="invalid-feedback">
                                                            Pilih Menu.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-floating mb-3">
                                                        <input type="number" class="form-control" id="floatingInput" placeholder="Jumlah Porsi" name="jumlah" required value="<?php echo $row['jumlah'] ?>">
                                                        <label for="floatingInput">Qty</label>
                                                        <div class="invalid-feedback">
                                                            Masukkan jumlah porsi.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput" placeholder="Catatan" name="catatan" value="<?php echo $row['catatan'] ?>">
                                                        <label for="floatingPassword">Catatan</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="edit_orderitem_validate" value="12345">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit -->

                        <!-- Modal Delete-->
                        <div class="modal fade" id="ModalDelete<?php echo $row['id_list_order'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-fullscreen-md-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Item Order</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="needs-validation" novalidate action="proses/proses_delete_orderitem.php" method="POST">
                                            <input type="hidden" value="<?php echo $row['id_list_order'] ?>" name="id">
                                            <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                            <input type="hidden" name="tempat" value="<?php echo $tempat ?>">
                                            <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                            <div class="col-lg-12">
                                                Apakah anda yakin ingin menghapus order <b><?php echo $row['nama_menu'] ?></b>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger" name="delete_orderitem_validate" value="12345">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Delete -->

                    <?php
                    }
                    ?>

                    <!-- Modal Bayar -->
                    <div class="modal fade" id="bayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pembayaran</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th scope="col">Menu</th>
                                                    <th scope="col">Harga</th>
                                                    <th scope="col">Qty</th>
                                                    <th scope="col">Catatan</th>
                                                    <th scope="col">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                foreach ($result as $row) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $row['nama_menu'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo number_format($row['harga'], 0, ',', '.') ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['jumlah'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['catatan'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo number_format($row['harganya'], 0, ',', '.') ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $total += $row['harganya'];
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="fw-bold">
                                                        Total Harga
                                                    </td>
                                                    <td colspan="5" class="fw-bold">
                                                        <?php echo number_format($total, 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <form class="needs-validation" novalidate action="proses/proses_bayar.php" method="POST">
                                        <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                        <input type="hidden" name="tempat" value="<?php echo $tempat ?>">
                                        <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                        <input type="hidden" name="total" value="<?php echo $total ?>">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="floatingInput" placeholder="Nominal Harga" name="uang" required>
                                                    <label for="floatingInput">Total Bayar</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan nominal uang.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="bayar_validate" value="12345">Bayar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Bayar -->

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-nowrap">
                                    <th scope="col">Menu</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($result as $row) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['nama_menu'] ?>
                                        </td>
                                        <td>
                                            <?php echo number_format($row['harga'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <?php echo $row['jumlah'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['catatan'] ?>
                                        </td>
                                        <td>
                                            <?php echo number_format($row['harganya'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-warning btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id_list_order'] ?>"><i class="bi bi-pen-fill"></i></button>
                                                <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-danger btn-sm me-1 btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id_list_order'] ?>"><i class="bi bi-trash2-fill"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                    $total += $row['harganya'];
                                }
                                ?>
                                <tr>
                                    <td colspan="4" class="fw-bold">
                                        Total Harga
                                    </td>
                                    <td colspan="5" class="fw-bold">
                                        <?php echo number_format($total, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <div>
                    <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary disabled" : "btn btn-success"; ?>" data-bs-toggle="modal" data-bs-target="#tambahItem"><i class="bi bi-plus-circle"></i> Item</button>
                    <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary disabled" : "btn btn-primary"; ?>" data-bs-toggle="modal" data-bs-target="#bayar"><i class="bi bi-wallet2"></i> Bayar</button>
                    <button onclick="printStruk()" class="btn btn-info">Print</button>
                </div>
            </div>
        </div>
    </div>

    <div id="strukContent" class="d-none">
        <style>
            #struk {
                font-family: "Arial", sans-serif;
                font-size: 16px;
                max-width: 300px;
                border: 1px solid #ccc;
                padding: 10px;
                width: 80mm;
            }
            #struk h4 {
                text-align: center;
                color: #333;
            }
            #struk p {
                margin: 5px 0;
            }
            #struk table {
                font-size: 16px;
                margin-top: 10px;
                width: 100%;
            }
            #struk th, #struk td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            #struk .total {
                font-weight: bold;
            }
        </style>
        <div id="struk">
            <h4>Struk Pembayaran Kedai Keboen Djati</h4>
            <p>Kode Order: <?php echo $kode ?></p>
            <p>Tempat: <?php echo $tempat ?></p>
            <p>Pelanggan: <?php echo $pelanggan ?></p>
            <p>Waktu Order: <?php echo date('d-m-Y H:i:s', strtotime($result[0]['waktu_order'])) ?></p>

            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($result as $row) { ?>
                        <tr>
                            <td><?php echo $row['nama_menu'] ?></td>
                            <td><?php echo $row['jumlah'] ?></td>
                            <td><?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?php echo number_format($row['harganya'], 0, ',', '.') ?></td>
                        </tr>
                    <?php
                        $total += $row['harganya'];
                    } ?>
                    <tr class="total">
                        <td colspan="3">Total Harga</td>
                        <td><?php echo number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function printStruk() {
            var strukContent = document.getElementById("strukContent").innerHTML;

            var printFrame = document.createElement("iframe");
            printFrame.style.display = 'none';
            document.body.appendChild(printFrame);
            printFrame.contentDocument.write(strukContent);
            printFrame.contentWindow.print();
            document.body.removeChild(printFrame);
        }
    </script>