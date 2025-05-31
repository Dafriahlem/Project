<?php
include '../includes/config.php';
checkRole('client');

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND end_date >= CURDATE() AND payment_status = 'paid'");
$stmt->execute([$user_id]);
$subscription = $stmt->fetch();


$today = strtolower(date('l'));
$french_days = [
    'monday' => 'lundi',
    'tuesday' => 'mardi',
    'wednesday' => 'mercredi',
    'thursday' => 'jeudi',
    'friday' => 'vendredi',
    'saturday' => 'samedi',
    'sunday' => 'dimanche'
];
$today_french = $french_days[$today];

$page_title = "Tableau de Bord Client";
include '../includes/header.php';
?>
<style>
    :root {
        --black: #0a0a0a;
        --dark-gray: #1a1a1a;
        --red: #e63946;
        --light-red: #ff4d5a;
        --light: #f8f9fa;
        --transition: all 0.3s ease;
        --warning: #ffc107;
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
        color: var(--light);
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

    .alert {
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-warning {
        background: rgba(255, 193, 7, 0.2);
        border: 1px solid rgba(255, 193, 7, 0.3);
        color: var(--warning);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--light);
        margin: 0;
    }

    .btn {
        background: var(--red);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
    }

    .btn:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="subscription.php"><i class="fas fa-credit-card"></i> Mon Abonnement</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        
        <?php if (isset($_SESSION['subscription_expired'])): ?>
            <div class="alert alert-warning">
                <p>Votre abonnement a expiré. Veuillez le renouveler pour continuer à profiter de nos services.</p>
                <a href="subscription.php" class="btn">Renouveler mon abonnement</a>
            </div>
            <?php unset($_SESSION['subscription_expired']); ?>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Mon Abonnement</h3>
                <p>
                    <?php if ($subscription): ?>
                        <?php 
                            $type_map = [
                                '1_month' => '1 mois',
                                '3_months' => '3 mois',
                                '6_months' => '6 mois',
                                '12_months' => '12 mois'
                            ];
                            echo $type_map[$subscription['type']];
                        ?>
                    <?php else: ?>
                        Aucun abonnement actif
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="stat-card">
                <h3>Date d'expiration</h3>
                <p>
                    <?php if ($subscription): ?>
                        <?php echo date('d/m/Y', strtotime($subscription['end_date'])); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

