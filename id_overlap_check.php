<?php
$host = 'localhost';
$user = 'root';
$password = 'gudwns13';
$dbname = 'ev_charging_recommendation';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

$loginId = $_POST['loginId'];

$sql = "SELECT * FROM member WHERE loginId='$loginId'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo 'true';
} else {
    echo 'false';
}

mysqli_close($conn);
?>