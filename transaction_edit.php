<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: transactions.php");
    exit;
}

// Fetch transaction
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->execute([$id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header("Location: transactions.php");
    exit;
}

// Fetch accounts
$accounts = $pdo->query("SELECT id, account_name FROM accounts ORDER BY account_name")->fetchAll();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date        = $_POST['transaction_date'];
    $type        = $_POST['type'];
    $account_id  = $_POST['account_id'];
    $amount      = $_POST['amount'];
    $description = $_POST['description'];

    $sql = "UPDATE transactions
            SET transaction_date=?, type=?, account_id=?, amount=?, description=?
            WHERE id=?";

    $pdo->prepare($sql)->execute([
        $date, $type, $account_id, $amount, $description, $id
    ]);

    header("Location: transactions.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<title>Edit Transaction</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Edit Transaction</h1>

<form method="post" class="bg-white p-4 rounded shadow max-w-lg">

<label class="block mb-1">Date</label>
<input type="date" name="transaction_date" value="<?= $transaction['transaction_date'] ?>" required class="w-full mb-3 p-2 border rounded">

<label class="block mb-1">Type</label>
<select name="type" class="w-full mb-3 p-2 border rounded">
  <option value="income" <?= $transaction['type']=='income'?'selected':'' ?>>Income</option>
  <option value="expense" <?= $transaction['type']=='expense'?'selected':'' ?>>Expense</option>
</select>

<label class="block mb-1">Account</label>
<select name="account_id" class="w-full mb-3 p-2 border rounded" required>
<?php foreach ($accounts as $a): ?>
  <option value="<?= $a['id'] ?>" <?= $a['id']==$transaction['account_id']?'selected':'' ?>>
    <?= htmlspecialchars($a['account_name']) ?>
  </option>
<?php endforeach; ?>
</select>

<label class="block mb-1">Amount</label>
<input type="number" step="0.01" name="amount" value="<?= $transaction['amount'] ?>" required class="w-full mb-3 p-2 border rounded">

<label class="block mb-1">Description</label>
<textarea name="description" class="w-full mb-3 p-2 border rounded"><?= htmlspecialchars($transaction['description']) ?></textarea>

<button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
<a href="transactions.php" class="ml-2 text-gray-600">Cancel</a>

</form>
</body>
</html>
