<?php
session_start();
require 'db.php';
$db = getDB();

// Fetch logs in reverse order by arrival time or worker ID (depending on your needs)
$logs = $db->query("SELECT * FROM worker_logs ORDER BY arrival_time DESC")->fetchAll(PDO::FETCH_NUM);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TimeLog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        /* Add some space between the buttons and the table */
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">TimeLog</h1>

        <form method="POST" action="scan.php" class="mb-4" id="scanForm">
            <div class="input-group">
                <input type="text" name="worker_id" class="form-control" placeholder="Scan Worker ID" required autofocus>
                <button type="submit" class="btn btn-primary">Log</button>
            </div>
        </form>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['type'] ?>"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message'], $_SESSION['type']); ?>
        <?php endif; ?>

        <!-- Move Export and Clear buttons here -->
        <div class="d-flex justify-content-between table-container">
            <a href="export.php" class="btn btn-success">Export to Excel</a>
            <a href="clear.php" class="btn btn-danger">Clear All Logs</a>
        </div>

        <!-- Table displaying logs -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Worker ID</th>
                    <th>Arrival Time</th>
                    <th>Leaving Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log[0] ?? '') ?></td>
                        <td><?= htmlspecialchars($log[1] ?? '') ?></td>
                        <td><?= htmlspecialchars($log[2] ?? '') ?></td>
                        <td><?= htmlspecialchars($log[3] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
