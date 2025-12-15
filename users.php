<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ADMIN ONLY */
if ($_SESSION['role_id'] != 1) {
    http_response_code(403);
    echo "Forbidden: Admin access only";
    exit;
}

$message = '';

/* ADD USER */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = (int)$_POST['role_id'];

    $stmt = $pdo->prepare(
        "INSERT INTO users (username, password, full_name, role_id)
         VALUES (?,?,?,?)"
    );

    try {
        $stmt->execute([$username, $password, $full_name, $role_id]);
        $message = "User created successfully";
    } catch (PDOException $e) {
        $message = "Error: Username already exists";
    }
}

/* FETCH USERS */
$users = $pdo->query(
    "SELECT u.id, u.username, u.full_name, r.role_name
     FROM users u
     JOIN users_roles r ON u.role_id = r.id
     ORDER BY u.id"
)->fetchAll();

/* ROLES */
$roles = $pdo->query("SELECT * FROM users_roles")->fetchAll();
?>
<!doctype html>
<html>
<head>
<title>User Management</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">User Management</h1>

<?php if ($message): ?>
<div class="mb-4 p-2 bg-blue-100 text-blue-800 rounded">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- ADD USER -->
<div class="bg-white p-4 rounded shadow mb-6">
<h2 class="font-semibold mb-3">Add New User</h2>

<form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-3">
  <input name="username" placeholder="Username" required class="border p-2 rounded">
  <input name="full_name" placeholder="Full Name" required class="border p-2 rounded">
  <input type="password" name="password" placeholder="Password" required class="border p-2 rounded">
  <select name="role_id" class="border p-2 rounded">
    <?php foreach ($roles as $r): ?>
      <option value="<?= $r['id'] ?>"><?= $r['role_name'] ?></option>
    <?php endforeach; ?>
  </select>
  <button class="bg-blue-600 text-white p-2 rounded col-span-full">
    Create User
  </button>
</form>
</div>

<!-- USER LIST -->
<div class="bg-white p-4 rounded shadow">
<table class="w-full text-sm">
<thead class="bg-gray-200">
<tr>
  <th class="p-2">ID</th>
  <th class="p-2">Username</th>
  <th class="p-2">Full Name</th>
  <th class="p-2">Role</th>
</tr>
</thead>
<tbody>
<?php foreach ($users as $u): ?>
<tr class="border-t">
  <td class="p-2"><?= $u['id'] ?></td>
  <td class="p-2"><?= htmlspecialchars($u['username']) ?></td>
  <td class="p-2"><?= htmlspecialchars($u['full_name']) ?></td>
  <td class="p-2"><?= htmlspecialchars($u['role_name']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</body>
</html>
