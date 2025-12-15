<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$accounts = $pdo->query("SELECT * FROM accounts")->fetchAll();

$receiptPath = null;

$receiptPath = null;

if (!empty($_FILES['receipt']['name'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
    $fileName = 'receipt_' . time() . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath);
    $receiptPath = $targetPath;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
  INSERT INTO transactions
  (type, account_id, amount, description, transaction_date, receipt)
  VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
  $_POST['type'],
  $_POST['account_id'],
  $_POST['amount'],
  $_POST['description'],
  $_POST['transaction_date'],
  $receiptPath
]);


    header("Location: transactions.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>Add Transaction</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-xl font-bold mb-4">Add Transaction</h1>

<form method="post" enctype="multipart/form-data"
      class="bg-white p-4 rounded shadow space-y-4">

  <select name="type" class="w-full border p-2" required>
    <option value="income">Income</option>
    <option value="expense">Expense</option>
  </select>

  <label class="block mb-1 font-semibold">Transaction Date</label>
<input type="date" name="transaction_date"
       class="w-full border rounded p-2 mb-4"
       required>


  <input type="number" step="0.01" name="amount" class="w-full border p-2" required>

  <select name="account_id" class="w-full border p-2" required>
    <?php foreach ($accounts as $a): ?>
      <option value="<?= $a['id'] ?>">
        <?= htmlspecialchars($a['account_name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <textarea name="description" class="w-full border p-2" placeholder="Description"></textarea>
  <label class="block font-semibold">Receipt (optional)</label>
  <input type="file" name="receipt"
       class="w-full border p-2"
       accept=".jpg,.jpeg,.png,.pdf, image">

  <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
</form>

</body>
</html>
