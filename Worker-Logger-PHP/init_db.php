<?php
$db = new PDO('sqlite:worker_logs.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("
    CREATE TABLE IF NOT EXISTS worker_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        worker_id TEXT NOT NULL,
        arrival_time TEXT,
        leaving_time TEXT
    )
");
echo "Database initialized.";
