<?php
session_start();
include "connect.php";
$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";
$tempat = (isset($_POST['tempat'])) ? htmlentities($_POST['tempat']) : "";
$pelanggan = (isset($_POST['pelanggan'])) ? htmlentities($_POST['pelanggan']) : "";

if (!empty($_POST['input_order_validate'])) {
    $select = mysqli_query($conn, "SELECT * FROM tb_order WHERE id_order = '$kode_order'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Order yang dimasukkan sudah ada");
                    window.location="../order"</script>
                    </script>';
    } else {
        $query = mysqli_query($conn, "INSERT INTO tb_order (id_order,tempat,pelanggan,pelayan) values ('$kode_order','$tempat','$pelanggan','$_SESSION[id_keboen]')");
        if ($query) {
            $message = '<script>alert("Order berhasil di input");
                    window.location="../?x=orderitem&order='.$kode_order.'&tempat='.$tempat.'&pelanggan='.$pelanggan.'"</script>';
        } else {
            $message = '<script>alert("Order gagal di input")</script>';
        }
    }
}
echo $message;
