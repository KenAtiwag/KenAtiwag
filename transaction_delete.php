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

// Get receipt path
$stmt = $pdo->prepare("SELECT receipt FROM transactions WHERE id = ?");
$stmt->execute([$id]);
$receipt = $stmt->fetchColumn();

// Delete receipt file if exists
if ($receipt && file_exists($receipt)) {
    unlink($receipt);
}

// Delete transaction
$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
$stmt->execute([$id]);

header("Location: transactions.php");
exit;
