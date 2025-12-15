<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$income = $pdo->query("
    SELECT SUM(amount) FROM transactions WHERE type='income'
")->fetchColumn() ?? 0;

$expense = $pdo->query("
    SELECT SUM(amount) FROM transactions WHERE type='expense'
")->fetchColumn() ?? 0;

$net = $income - $expense;
?>
<!doctype html>
<html>
<head>
<title>Income Statement</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Income Statement</h1>

<div class="bg-white p-6 rounded shadow w-96">
  <p><strong>Total Income:</strong> ₱ <?= number_format($income, 2) ?></p>
  <p><strong>Total Expenses:</strong> ₱ <?= number_format($expense, 2) ?></p>
  <hr class="my-3">
  <p class="text-lg font-bold">
    Net Income: ₱ <?= number_format($net, 2) ?>
  </p>
</div>

</body>
</html>
