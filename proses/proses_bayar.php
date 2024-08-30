<?php
session_start();
include "connect.php";
$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";
$tempat = (isset($_POST['tempat'])) ? htmlentities($_POST['tempat']) : "";
$pelanggan = (isset($_POST['pelanggan'])) ? htmlentities($_POST['pelanggan']) : "";
$total = (isset($_POST['total'])) ? htmlentities($_POST['total']) : "";
$uang = (isset($_POST['uang'])) ? htmlentities($_POST['uang']) : "";
$kembalian = $uang - $total;

if (!empty($_POST['bayar_validate'])) {
    if ($kembalian < 0) {
        $message = '<script>alert("Nominal uang tidak mencukupi");
                    window.location="../?x=orderitem&order=' . $kode_order . '&tempat=' . $tempat . '&pelanggan=' . $pelanggan . '"</script>';
    } else {
            $query = mysqli_query($conn, "INSERT INTO tb_bayar (id_bayar,nominal_uang,total_bayar) values ('$kode_order','$uang','$total')");
            if ($query) {
                $message = '<script>alert("Pembayaran berhasil \nUang Kembalian Rp. '.$kembalian.'");
                    window.location="../?x=orderitem&order=' . $kode_order . '&tempat=' . $tempat . '&pelanggan=' . $pelanggan . '"</script>';
            } else {
                $message = '<script>alert("Pembayaran gagal");
                        window.location="../?x=orderitem&order=' . $kode_order . '&tempat=' . $tempat . '&pelanggan=' . $pelanggan . '"</script>';
            }
        }
    }
echo $message;
