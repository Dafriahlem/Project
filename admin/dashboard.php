<?php
include '../includes/config.php';
checkRole('admin');

$page_title = "Tableau de bord Admin";



$clients_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn();
$coaches_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'coach'")->fetchColumn();
$active_subscriptions = $pdo->query("SELECT COUNT(*) FROM subscriptions WHERE payment_status = 'paid'")->fetchColumn();



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
        width: 240px;
        background: var(--dark-gray);
        padding: 20px;
        border-right: 1px solid rgba(230, 57, 70, 0.2);
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
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="subscriptions.php"><i class="fas fa-credit-card"></i> Abonnements</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Tableau de bord Administrateur</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Clients</h3>
                <p><?= $clients_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Coachs</h3>
                <p><?= $coaches_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Abonnements actifs</h3>
                <p><?= $active_subscriptions; ?></p>
            </div>
        </div>

    
    </div>
</div>


