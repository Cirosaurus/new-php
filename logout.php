<?php
session_start();
session_destroy(); // Hancurkan sesi
header("Location: login.php"); // Balik ke login
exit;
?>