<?php 

$konek = mysqli_connect("localhost", "root", "", "purnamamandiri");

// Check connection
if (mysqli_connect_errno()){
    die("Koneksi database gagal : " . mysqli_connect_error());
} else {
    echo "";
}


?>