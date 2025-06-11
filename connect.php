<?php
$conn = new mysqli("localhost", "root", "", "dokanelrvl");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>