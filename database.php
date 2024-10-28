<?php
$conn = mysqli_connect("localhost", "root", "", "lgutestdb");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>