<?php
function getDB() {
    $db = new PDO('sqlite:worker_logs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

function getLastScanTime($worker_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT MAX(arrival_time), MAX(leaving_time) FROM worker_logs WHERE worker_id = ?");
    $stmt->execute([$worker_id]);
    $result = $stmt->fetch(PDO::FETCH_NUM);
    $times = array_filter($result, fn($t) => $t !== null);
    return $times ? max(array_map(fn($t) => new DateTime($t), $times)) : null;
}

function logWorker($worker_id, &$message) {
    $db = getDB();
    // You have to fix manually the correct time (+3 hours)
    $now = (new DateTime())->modify('+3 hours');
    $nowStr = $now->format('Y-m-d H:i:s');
    $lastScan = getLastScanTime($worker_id);

    if ($lastScan && ($now->getTimestamp() - $lastScan->getTimestamp()) < 3) {
        $message = "Scanned too quickly.";
        return false;
    }

    // Check if already clocked in
    $stmt = $db->prepare("SELECT id FROM worker_logs WHERE worker_id = ? AND leaving_time IS NULL");
    $stmt->execute([$worker_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $db->prepare("UPDATE worker_logs SET leaving_time = ? WHERE id = ?");
        $stmt->execute([$nowStr, $existing['id']]);
        $message = "Logged out at $nowStr";
    } else {
        $stmt = $db->prepare("INSERT INTO worker_logs (worker_id, arrival_time) VALUES (?, ?)");
        $stmt->execute([$worker_id, $nowStr]);
        $message = "Logged in at $nowStr";
    }

    return true;
}
