<?php
session_start();
$db = new PDO('sqlite:worker_logs.db');
$db->exec("DELETE FROM worker_logs");
$_SESSION['message'] = "All logs cleared.";
$_SESSION['type'] = "success";
header('Location: index.php');
