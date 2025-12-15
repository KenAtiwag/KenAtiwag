<?php
require 'config.php'; require 'inc/session.php';
if ($_SERVER['REQUEST_METHOD']=='POST' && !empty($_POST['role_name'])){
    $stmt = $pdo->prepare('INSERT INTO users_roles (role_name) VALUES (?)');
    $stmt->execute([$_POST['role_name']]);
    header('Location: roles.php');
    exit;
}
$roles = $pdo->query('SELECT * FROM users_roles')->fetchAll(PDO::FETCH_ASSOC);
echo '<h3>User Roles</h3><form method=post><input name=role_name><button>Add</button></form><ul>';
foreach($roles as $r) echo '<li>'.$r['role_name'].'</li>';
echo '</ul>';