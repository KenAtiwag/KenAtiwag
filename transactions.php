<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "
SELECT 
  t.id,
  t.transaction_date,
  t.type,
  t.amount,
  t.description,
  t.receipt,
  a.account_code,
  a.account_name
FROM transactions t
JOIN accounts a ON t.account_id = a.id
ORDER BY t.transaction_date DESC
";

$transactions = $pdo->query($sql)->fetchAll();

?>
<!doctype html>
<html>
<head>
<title>Transactions</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Transactions</h1>
<a href="transaction_add.php" class="bg-blue-600 text-white px-4 py-2 rounded">Add Transaction</a>

<table class="w-full bg-white shadow rounded mt-4">
<thead class="bg-gray-200">
<tr>
  <th class="p-2">Date</th>
  <th class="p-2">Type</th>
  <th class="p-2">Account</th>
  <th class="p-2">Amount</th>
  <th class="p-2">Description</th>
  <th class="p-2">Actions</th>
  <th class="p-2">Receipt</th>

</tr>
</thead>
<tbody>
<?php foreach ($transactions as $t): ?>
<tr class="border-t">
  <td class="p-2"><?= htmlspecialchars($t['transaction_date']) ?></td>
  <td class="p-2"><?= ucfirst($t['type']) ?></td>
  <td class="p-2">
    <?= htmlspecialchars($t['account_code']) ?> - <?= htmlspecialchars($t['account_name']) ?>
  </td>
  <td class="p-2">₱ <?= number_format($t['amount'],2) ?></td>
  <td class="p-2"><?= htmlspecialchars($t['description']) ?></td>

  <!-- ACTIONS -->
  <td class="p-2">
    <a href="transaction_edit.php?id=<?= $t['id'] ?>" class="text-blue-600">Edit</a>
    |
    <a href="transaction_delete.php?id=<?= $t['id'] ?>"
       onclick="return confirm('Delete this transaction?')"
       class="text-red-600">Delete</a>
  </td>

  <!-- RECEIPT -->
  <td class="p-2">
    <?php if (!empty($t['receipt'])): ?>
      <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $t['receipt'])): ?>
        <a href="<?= htmlspecialchars($t['receipt']) ?>" target="_blank">
          <img src="<?= htmlspecialchars($t['receipt']) ?>"
              class="w-16 h-16 object-cover border rounded">
        </a>
      <?php else: ?>
        <a href="<?= htmlspecialchars($t['receipt']) ?>"
          target="_blank"
          class="text-blue-600 underline">
          View File
        </a>
      <?php endif; ?>
    <?php else: ?>
      —
    <?php endif; ?>
</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</body>
</html>
