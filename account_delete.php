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

/* Check if account has transactions */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE account_id = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    die("Cannot delete account. Transactions exist.");
}

/* Delete account */
$stmt = $pdo->prepare("DELETE FROM accounts WHERE id = ?");
$stmt->execute([$id]);

header("Location: accounts.php");
exit;
?>