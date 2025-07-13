<?php
session_start();
require 'db.php';

$worker_id = $_POST['worker_id'] ?? '';
if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $worker_id)) {
    $_SESSION['message'] = "Invalid Worker ID.";
    $_SESSION['type'] = "danger";
    header('Location: index.php');
    exit;
}

$success = logWorker($worker_id, $msg);
$_SESSION['message'] = $msg;
$_SESSION['type'] = $success ? "success" : "warning";
header('Location: index.php');
