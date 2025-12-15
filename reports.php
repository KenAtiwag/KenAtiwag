<?php
session_start();
require 'config.php';
?>
<!doctype html>
<html>
<head>
<title>Reports</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Reports</h1>

<div class="bg-white p-4 rounded shadow w-80 space-y-2">
  <a href="income_statement.php" class="block py-2 px-2 hover:bg-gray-100">
  Income Statement
</a>

<a href="monthly_summary.php" class="block py-2 px-2 hover:bg-gray-100">
  Monthly Summary
</a>

<a href="ledger.php" class="block py-2 px-2 hover:bg-gray-100">
  General Ledger
</a>

</div>

</body>
</html>
