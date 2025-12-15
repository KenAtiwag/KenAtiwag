<?php
require 'config.php';

$data = $pdo->query("
SELECT 
  DATE_FORMAT(created_at, '%Y-%m') AS month,
  SUM(CASE WHEN type='income' THEN amount ELSE 0 END) income,
  SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) expense
FROM transactions
GROUP BY month
ORDER BY month DESC
")->fetchAll();
?>
<!doctype html>
<html>
<head>
<title>Monthly Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">

<h1 class="text-xl font-bold mb-4">Monthly Summary</h1>

<table class="bg-white shadow rounded w-full">
<thead class="bg-gray-200">
<tr>
  <th class="p-2">Month</th>
  <th class="p-2">Income</th>
  <th class="p-2">Expense</th>
  <th class="p-2">Net</th>
</tr>
</thead>
<tbody>
<?php foreach ($data as $r): ?>
<tr class="border-t">
  <td class="p-2"><?= $r['month'] ?></td>
  <td class="p-2">₱ <?= number_format($r['income'],2) ?></td>
  <td class="p-2">₱ <?= number_format($r['expense'],2) ?></td>
  <td class="p-2">₱ <?= number_format($r['income'] - $r['expense'],2) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</body>
</html>
