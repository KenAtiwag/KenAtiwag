<?php
require_once __DIR__.'/inc/session.php';
// chart_data.php - returns JSON for monthly totals used by Chart.js
header('Content-Type: application/json');
require 'config.php';
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, SUM(CASE WHEN type='income' THEN amount ELSE 0 END) AS income, SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) AS expense FROM transactions GROUP BY ym ORDER BY ym ASC");
$rows = $stmt->fetchAll();
$labels = []; $income = []; $expense = [];
foreach($rows as $r){ $labels[]=$r['ym']; $income[] = (float)$r['income']; $expense[] = (float)$r['expense']; }
echo json_encode(['labels'=>$labels,'income'=>$income,'expense'=>$expense]);
?>