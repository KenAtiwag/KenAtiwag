<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* Fetch all accounts */
$accounts = $pdo->query("
    SELECT id, account_code, account_name, account_type
    FROM accounts
    ORDER BY account_code
")->fetchAll();
?>
<!doctype html>
<html>
<head>
<title>General Ledger</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-6">General Ledger</h1>

<?php foreach ($accounts as $acc): ?>

<div class="bg-white shadow rounded mb-8 p-4">
  <h2 class="text-lg font-semibold mb-2">
    <?= htmlspecialchars($acc['account_code']) ?> - <?= htmlspecialchars($acc['account_name']) ?>
    <span class="text-sm text-gray-500">(<?= $acc['account_type'] ?>)</span>
  </h2>

  <table class="w-full text-sm border">
    <thead class="bg-gray-200">
      <tr>
        <th class="p-2">Date</th>
        <th class="p-2">Description</th>
        <th class="p-2">Debit</th>
        <th class="p-2">Credit</th>
        <th class="p-2">Balance</th>
      </tr>
    </thead>
    <tbody>

<?php
$balance = 0;

$rows = $pdo->prepare("
    SELECT transaction_date, type, amount, description
    FROM transactions
    WHERE account_id = ?
    ORDER BY transaction_date
");
$rows->execute([$acc['id']]);
$entries = $rows->fetchAll();

foreach ($entries as $e):
    $debit = 0;
    $credit = 0;

    if ($e['type'] === 'expense') {
        $debit = $e['amount'];
        $balance += $debit;
    } else {
        $credit = $e['amount'];
        $balance -= $credit;
    }
?>
<tr class="border-t">
  <td class="p-2"><?= htmlspecialchars($e['transaction_date']) ?></td>
  <td class="p-2"><?= htmlspecialchars($e['description']) ?></td>
  <td class="p-2"><?= $debit ? number_format($debit,2) : '' ?></td>
  <td class="p-2"><?= $credit ? number_format($credit,2) : '' ?></td>
  <td class="p-2 font-semibold"><?= number_format($balance,2) ?></td>
</tr>
<?php endforeach; ?>

    </tbody>
  </table>
</div>

<?php endforeach; ?>

</body>
</html>
