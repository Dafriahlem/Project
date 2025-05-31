<?php
include '../includes/config.php';
checkRole('admin');


if (isset($_GET['change_role'])) {
    $user_id = $_GET['user_id'];
    $new_role = $_GET['new_role'];

    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);

    $_SESSION['success_message'] = "Rôle de l'utilisateur mis à jour avec succès";
    redirect('users.php');
}


if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    if ($user_id == 1) {
        $_SESSION['error_message'] = "Vous ne pouvez pas supprimer le compte administrateur principal";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['success_message'] = "Utilisateur supprimé avec succès";
    }

    redirect('users.php');
}


$stmt = $pdo->query("SELECT * FROM users WHERE id != 1  ORDER BY created_at DESC ");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Gestion des Utilisateurs";

?>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    :root {
        --black: #0a0a0a;
        --dark-gray: #1a1a1a;
        --red: #e63946;
        --light-red: #ff4d5a;
        --light: #f8f9fa;
        --transition: all 0.3s ease;
    }

    body {
        background: var(--black);
        color: var(--light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard {
        display: flex;
        min-height: 100vh;
    }
    .sidebar {
    background: var(--dark-gray);
        width: 220px;
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar ul li {
        margin-bottom: 10px;
    }
    .sidebar ul li a {
        color: var(--light);
        text-decoration: none;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        font-weight: 600;
        border-left: 4px solid transparent;
        transition: var(--transition);
    }
    .sidebar ul li a.active,
    .sidebar ul li a:hover {
        border-left: 4px solid var(--red);
        background: rgba(230, 57, 70, 0.1);
        color: var(--light-red);
    }
    .sidebar ul li a i {
        margin-right: 10px;
    }

    .main-content {
        flex: 1;
        padding: 40px;
        background: var(--black);
    }

    h2 {
        font-size: 2rem;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-weight: bold;
    }

    .alert-success {
        background-color: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
        border-left: 4px solid #4CAF50;
    }

    .alert-danger {
        background-color: rgba(244, 67, 54, 0.1);
        color: #f44336;
        border-left: 4px solid #f44336;
    }

    .card {
        background: var(--dark-gray);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        color: var(--light);
    }

    table thead {
        background: rgba(255, 255, 255, 0.05);
    }

    table th,
    table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    table tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    select {
        background: var(--black);
        color: var(--light);
        border: 1px solid var(--red);
        padding: 5px 10px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn {
        padding: 6px 14px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-danger {
        background-color: var(--red);
        color: white;
    }

    .btn-danger:hover {
        background-color: var(--light-red);
    }

    .btn-sm {
        font-size: 0.9rem;
    }
</style>
<?php include_once "../includes/header.php" ?>
<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="users.php" class="active"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="subscriptions.php"><i class="fas fa-credit-card"></i> Abonnements</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Gestion des Utilisateurs</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id']; ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="get" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                    <select name="new_role" onchange="this.form.submit()">
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="coach" <?= $user['role'] == 'coach' ? 'selected' : ''; ?>>Coach</option>
                                        <option value="client" <?= $user['role'] == 'client' ? 'selected' : ''; ?>>Client</option>
                                    </select>
                                    <input type="hidden" name="change_role" value="1">
                                </form>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != 1): ?>
                                    <a href="users.php?delete=<?= $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">Supprimer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

