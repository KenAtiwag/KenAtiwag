<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$accounts = $pdo->query("SELECT * FROM accounts ORDER BY account_code")->fetchAll();
?>
<!doctype html>
<html>
<head>
<title>Accounts</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Chart of Accounts</h1>

<a href="account_add.php" class="bg-blue-600 text-white px-4 py-2 rounded">
  Add Account
</a>

<table class="w-full bg-white shadow rounded mt-4">
<thead class="bg-gray-200">
<tr>
  <th class="p-2">Code</th>
  <th class="p-2">Name</th>
  <th class="p-2">Type</th>
  <th class="p-2">Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($accounts as $a): ?>
<tr class="border-t">
  <td class="p-2"><?= htmlspecialchars($a['account_code']) ?></td>
  <td class="p-2"><?= htmlspecialchars($a['account_name']) ?></td>
  <td class="p-2"><?= htmlspecialchars($a['account_type']) ?></td>
  <td class="p-2">
    <a href="account_edit.php?id=<?= $a['id'] ?>" class="text-blue-600 ">Edit</a>
|
<a href="account_delete.php?id=<?= $a['id'] ?>"
   onclick="return confirm('Delete this account?')"
   class="text-red-600">Delete</a>

  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</body>
</html>
