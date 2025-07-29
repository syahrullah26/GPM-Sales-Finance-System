<?php 

$konek = mysqli_connect("localhost", "root", "", "purnamaMandiri");

// Check connection
if (mysqli_connect_errno()){
    die("Koneksi database gagal : " . mysqli_connect_error());
} else {
    echo "";
}


?>