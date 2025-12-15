<?php
require 'config.php';

$new = password_hash('admin123', PASSWORD_DEFAULT);

$pdo->prepare("UPDATE users SET password=? WHERE username='admin'")
    ->execute([$new]);

echo "Admin password reset to admin123";
