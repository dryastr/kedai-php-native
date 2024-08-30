<?php
session_start();
include "connect.php";
$nama = (isset($_POST['nama'])) ? htmlentities($_POST['nama']) : "";
$nohp = (isset($_POST['no_hp'])) ? htmlentities($_POST['no_hp']) : "";
$alamat = (isset($_POST['alamat'])) ? htmlentities($_POST['alamat']) : "";

if (!empty($_POST['ubah_profile_validate'])) {
    $query = mysqli_query($conn, "UPDATE tb_user SET nama='$nama', no_hp='$nohp', alamat='$alamat' WHERE username = '$_SESSION[username_keboen]'");
    if ($query) {
        $message = '<script>alert("Data profile berhasil di ubah");
                    window.history.back()</script>';
    } else {
        $message = '<script>alert("Data profile gagal di ubah");
                    window.history.back()</script>';
    }
    } else {
        $message = '<script>alert("Terjadi kesalahan");
                    window.history.back()</script>';
    }
echo $message;
?>