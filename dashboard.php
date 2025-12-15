<?php
require 'config.php'; require 'inc/session.php'; require 'inc/auth.php'; require_login();
include 'inc/header.php';
$revenue = $pdo->query("SELECT COALESCE(SUM(ji.credit)-SUM(ji.debit),0) FROM journal_items ji JOIN accounts a ON ji.account_id=a.id WHERE LOWER(a.account_name) LIKE '%revenue%'")->fetchColumn();
$expenses = $pdo->query("SELECT COALESCE(SUM(ji.debit)-SUM(ji.credit),0) FROM journal_items ji JOIN accounts a ON ji.account_id=a.id WHERE LOWER(a.account_name) LIKE '%expense%'")->fetchColumn();
$cash = $pdo->query("SELECT COALESCE(SUM(ji.debit)-SUM(ji.credit),0) FROM journal_items ji JOIN accounts a ON ji.account_id=a.id WHERE LOWER(a.account_name) LIKE '%cash%'")->fetchColumn();
$count_tx = $pdo->query("SELECT COUNT(*) FROM journal_entries")->fetchColumn();
?>
<div class="row g-3">
  <div class="col-md-3"><div class="card p-3"><h5>Revenue</h5><p><?php echo number_format($revenue,2); ?></p></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Expenses</h5><p><?php echo number_format($expenses,2); ?></p></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Cash</h5><p><?php echo number_format($cash,2); ?></p></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Transactions</h5><p><?php echo $count_tx; ?></p></div></div>
</div>
<?php include 'inc/footer.php'; ?>