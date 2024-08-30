<?php
session_start();
include "connect.php";
$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";
$tempat = (isset($_POST['tempat'])) ? htmlentities($_POST['tempat']) : "";
$pelanggan = (isset($_POST['pelanggan'])) ? htmlentities($_POST['pelanggan']) : "";

if (!empty($_POST['edit_order_validate'])) {
    $query = mysqli_query($conn, "UPDATE tb_order SET tempat='$tempat',pelanggan='$pelanggan' WHERE id_order = '$kode_order'");
    if ($query) {
        $message = '<script>alert("Order berhasil diubah");
                    window.location="../order"</script>';
    } else {
        $message = '<script>alert("Order gagal diubah");
                    window.location="../order"</script>';
    }
}
echo $message;
