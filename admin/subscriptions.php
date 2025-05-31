<?php
include '../includes/config.php';
checkRole('admin');

$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$where = [];
$params = [];

if (!empty($status)) {
    $where[] = "s.payment_status = ?";
    $params[] = $status;
}

if (!empty($search)) {
    $where[] = "(u.username LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT s.*, u.username, u.email, u.phone 
        FROM subscriptions s
        JOIN users u ON s.user_id = u.id
        $where_clause
        ORDER BY s.start_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$subscriptions = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $sub_id = intval($_POST['sub_id']);
    $new_status = in_array($_POST['new_status'], ['pending', 'paid', 'expired', 'canceled']) ? $_POST['new_status'] : 'pending';

    $stmt = $pdo->prepare("UPDATE subscriptions SET payment_status = ? WHERE id = ?");
    $stmt->execute([$new_status, $sub_id]);

    $_SESSION['success_message'] = "Statut d'abonnement mis à jour avec succès";
    header("Location: subscriptions.php");
    exit();
}

if (isset($_GET['delete'])) {
    $sub_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = ?");
    $stmt->execute([$sub_id]);

    $_SESSION['success_message'] = "Abonnement supprimé avec succès";
    header("Location: subscriptions.php");
    exit();
}

$page_title = "Gestion des Abonnements";

?>

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
        margin-bottom: 25px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 2.2rem;
        color: var(--light);
    }
    h2 i {
        color: var(--red);
    }
    .alert {
        background-color: #1b401b;
        border: 1px solid #2e7d2e;
        color: #b7e1b7;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 6px;
    }
    .card {
        background: var(--dark-gray);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(230, 57, 70, 0.15);
        margin-bottom: 30px;
    }
    .filters .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        flex: 1 1 200px;
    }
    label {
        color: var(--light);
        margin-bottom: 6px;
        font-weight: 600;
    }
    select, input[type="text"] {
        padding: 10px 14px;
        border-radius: 6px;
        border: none;
        background: #2b2b2b;
        color: var(--light);
        font-size: 1rem;
        transition: var(--transition);
    }
    select:focus, input[type="text"]:focus {
        outline: 2px solid var(--red);
        background: #3a3a3a;
    }
    .btn {
        background: var(--red);
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        text-decoration: none;
    }
    .btn:hover {
        background: var(--light-red);
        box-shadow: 0 6px 20px rgba(230, 57, 70, 0.4);
        transform: translateY(-3px);
    }
    .btn-secondary {
        background: #444;
        color: var(--light);
    }
    .btn-secondary:hover {
        background: #666;
        color: var(--light-red);
    }
    .table-responsive {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        color: var(--light);
    }
    thead {
        background: var(--red);
        color: var(--light);
    }
    th, td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(230, 57, 70, 0.3);
        text-align: left;
        vertical-align: middle;
    }
    tbody tr:hover {
        background: rgba(230, 57, 70, 0.15);
    }
    .text-center {
        text-align: center;
    }
    .status-form select {
        background: #2b2b2b;
        color: var(--light);
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid rgba(230, 57, 70, 0.3);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }
    .status-pending {
        border-color: #ffb347;
        color: #ffb347;
    }
    .status-paid {
        border-color: #4CAF50;
        color: #4CAF50;
    }
    .status-expired {
        border-color: #d9534f;
        color: #d9534f;
    }
    .status-canceled {
        border-color: #999999;
        color: #999999;
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 6px;
        background: var(--red);
        border: none;
        color: var(--light);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-sm:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }
    .btn-sm.btn-danger {
        background: #a83232;
    }
    .btn-sm.btn-danger:hover {
        background: #ff4d5a;
    }
    h2 {
        font-size: 2rem;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: var(--dark-gray);
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid rgba(230, 57, 70, 0.1);
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }

    .stat-card h3 {
        font-size: 1.4rem;
        margin-bottom: 10px;
        color: var(--light-red);
    }

    .stat-card p {
        font-size: 2rem;
        font-weight: bold;
        color: var(--light);
    }

    .recent-activities {
        background: var(--dark-gray);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid rgba(230, 57, 70, 0.1);
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
    .recent-activities h3 {
        margin-bottom: 15px;
        color: var(--red);
    }

    .recent-activities ul {
        list-style: none;
        padding-left: 0;
    }

    .recent-activities ul li {
        margin-bottom: 10px;
        padding-left: 1rem;
        position: relative;
    }

    .recent-activities ul li::before {
        content: '•';
        color: var(--light-red);
        font-size: 1.2rem;
        position: absolute;
        left: 0;
        top: 0;
    }
</style>
<?php include_once "../includes/header.php" ?>
<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="subscriptions.php" class="active"><i class="fas fa-credit-card"></i> Abonnements</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2><i class="fas fa-credit-card"></i> Gestion des Abonnements</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert">
                <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card filters">
            <form method="get" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Statut de Paiement:</label>
                        <select id="status" name="status">
                            <option value="">Tous</option>
                            <option value="paid" <?= $status == 'paid' ? 'selected' : '' ?>>Payé</option>
                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>En Attente</option>
                            <option value="expired" <?= $status == 'expired' ? 'selected' : '' ?>>Expiré</option>
                            <option value="canceled" <?= $status == 'canceled' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="search">Recherche:</label>
                        <input type="text" id="search" name="search" placeholder="Nom ou email" value="<?= htmlspecialchars($search) ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn"><i class="fas fa-filter"></i> Filtrer</button>
                        <a href="subscriptions.php" class="btn btn-secondary"><i class="fas fa-redo"></i> Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Type d'Abonnement</th>
                            <th>Prix</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subscriptions)): ?>
                            <tr>
                                <td colspan="9" class="text-center">Aucun abonnement trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subscriptions as $sub): ?>
                                <tr>
                                    <td><?= $sub['id'] ?></td>
                                    <td><?= htmlspecialchars($sub['username']) ?></td>
                                    <td><?= htmlspecialchars($sub['email']) ?></td>
                                    <td>
                                        <?php
                                            $type_names = [
                                                '1_month' => '1 Mois',
                                                '3_months' => '3 Mois',
                                                '6_months' => '6 Mois',
                                                '12_months' => '1 An'
                                            ];
                                            echo $type_names[$sub['type']] ?? $sub['type'];
                                        ?>
                                    </td>
                                    <td><?= number_format($sub['price'], 0, ',', ' ') ?> DZD</td>
                                    <td><?= date('d/m/Y', strtotime($sub['start_date'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($sub['end_date'])) ?></td>
                                    <td>
                                        <form method="post" class="status-form">
                                            <input type="hidden" name="sub_id" value="<?= $sub['id'] ?>">
                                            <select name="new_status" onchange="this.form.submit()" class="status-select status-<?= $sub['payment_status'] ?>">
                                                <option value="pending" <?= $sub['payment_status'] == 'pending' ? 'selected' : '' ?>>En Attente</option>
                                                <option value="paid" <?= $sub['payment_status'] == 'paid' ? 'selected' : '' ?>>Payé</option>
                                                <option value="expired" <?= $sub['payment_status'] == 'expired' ? 'selected' : '' ?>>Expiré</option>
                                                <option value="canceled" <?= $sub['payment_status'] == 'canceled' ? 'selected' : '' ?>>Annulé</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="subscriptions.php?delete=<?= $sub['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement?')">supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

