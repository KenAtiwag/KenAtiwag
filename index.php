<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'User';

/* Dashboard queries */
$totIncome = $pdo->query("
    SELECT COALESCE(SUM(amount),0)
    FROM transactions
    WHERE type='income'
")->fetchColumn();

$totExpense = $pdo->query("
    SELECT COALESCE(SUM(amount),0)
    FROM transactions
    WHERE type='expense'
")->fetchColumn();


$recentStmt = $pdo->query("
    SELECT * FROM transactions
    ORDER BY created_at DESC
    LIMIT 5
");

$recent = $recentStmt ? $recentStmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Accounting System</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
<div class="flex">

<aside class="w-64 bg-white border-r p-4 hidden md:block">
  <h3 class="font-bold text-lg mb-4">Accounting</h3>
  <nav class="space-y-2 text-sm">
    <a href="index.php" class="block py-2 px-2 hover:bg-gray-100">Dashboard</a>
    <a href="transactions.php" class="block py-2 px-2 hover:bg-gray-100">Transactions</a>
    <a href="accounts.php" class="block py-2 px-2 hover:bg-gray-100">Accounts</a>
    <a href="reports.php" class="block py-2 px-2 hover:bg-gray-100">Reports</a>
    <a href="users.php" class="block py-2 px-2 hover:bg-gray-100">Users</a>
    <a href="logout.php" class="block py-2 px-2 hover:bg-gray-100">Logout</a>
  </nav>
</aside>

<main class="flex-grow p-6">
  <h1 class="text-2xl font-bold mb-1">Dashboard</h1>
  <p class="text-sm text-gray-600 mb-6">Welcome, <?= htmlspecialchars($username) ?></p>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Total Income</div>
      <div class="text-2xl font-bold">₱ <?= number_format($totIncome,2) ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Total Expenses</div>
      <div class="text-2xl font-bold">₱ <?= number_format($totExpense,2) ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-
