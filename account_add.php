<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO accounts (account_code, account_name, account_type, description)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['code'],
        $_POST['name'],
        $_POST['type'],
        $_POST['description']
    ]);
    header("Location: accounts.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<title>Add Account</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">

<h1 class="text-xl font-bold mb-4">Add Account</h1>

<form method="post" class="bg-white p-4 rounded shadow w-96">
  <input name="code" placeholder="Account Code" class="w-full mb-2 p-2 border" required>
  <input name="name" placeholder="Account Name" class="w-full mb-2 p-2 border" required>
  <select name="type" class="w-full mb-2 p-2 border">
    <option>Asset</option>
    <option>Liability</option>
    <option>Equity</option>
    <option>Revenue</option>
    <option>Expense</option>
  </select>
  <textarea name="description" placeholder="Description" class="w-full mb-2 p-2 border"></textarea>
  <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
</form>

</body>
</html>
