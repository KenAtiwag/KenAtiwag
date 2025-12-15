<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: accounts.php");
    exit;
}

$id = $_GET['id'];

/* Fetch account */
$stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = ?");
$stmt->execute([$id]);
$account = $stmt->fetch();

if (!$account) {
    die("Account not found");
}

/* Update account */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE accounts
        SET account_code = ?, account_name = ?, account_type = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['account_code'],
        $_POST['account_name'],
        $_POST['account_type'],
        $id
    ]);

    header("Location: accounts.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>Edit Account</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-xl font-bold mb-4">Edit Account</h1>

<form method="post" class="bg-white p-4 rounded shadow space-y-4 w-96">
    <input type="text" name="account_code"
           value="<?= htmlspecialchars($account['account_code']) ?>"
           class="w-full border p-2"
           required>

    <input type="text" name="account_name"
           value="<?= htmlspecialchars($account['account_name']) ?>"
           class="w-full border p-2"
           required>

    <select name="account_type" class="w-full border p-2" required>
        <?php
        $types = ['Asset','Liability','Equity','Revenue','Expense'];
        foreach ($types as $type):
        ?>
        <option value="<?= $type ?>"
            <?= $account['account_type'] === $type ? 'selected' : '' ?>>
            <?= $type ?>
        </option>
        <?php endforeach; ?>
    </select>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Update Account
    </button>
</form>

</body>
</html>
