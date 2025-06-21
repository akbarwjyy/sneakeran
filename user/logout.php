<?php
session_start(); // Sudah ada
session_destroy(); // Sudah ada
header("Location: login_user.php"); // Sudah benar, mengarah ke user/login_user.php
exit();
