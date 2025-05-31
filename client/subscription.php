<?php
include '../includes/config.php';
checkRole('client');

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY end_date DESC");
$stmt->execute([$user_id]);
$subscriptions = $stmt->fetchAll();


$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND end_date >= CURDATE() AND payment_status = 'paid'");
$stmt->execute([$user_id]);
$current_subscription = $stmt->fetch();


$subscription_types = [
    '1_month' => [
        'name' => '1 mois',
        'price' => 5000,
        'duration' => '+1 month'
    ],
    '3_months' => [
        'name' => '3 mois',
        'price' => 13500,
        'duration' => '+3 months'
    ],
    '6_months' => [
        'name' => '6 mois',
        'duration' => '+6 months',
        'price' => 24000
    ],
    '12_months' => [
        'name' => '12 mois',
        'duration' => '+12 months',
        'price' => 42000
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subscribe'])) {
    $type = $_POST['type'];
    
    if (!array_key_exists($type, $subscription_types)) {
        $_SESSION['error_message'] = "Type d'abonnement invalide";
        redirect('subscription.php');
    }
    
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime($subscription_types[$type]['duration']));
    $price = $subscription_types[$type]['price'];
    
    $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, type, price, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $price, $start_date, $end_date]);
    
    $_SESSION['success_message'] = "Abonnement créé avec succès. Veuillez effectuer le paiement.";
    redirect('payment.php?subscription_id=' . $pdo->lastInsertId());
}

$page_title = "Mon Abonnement";
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
        --success: #28a745;
        --danger: #dc3545;
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

    .card {
        background: var(--dark-gray);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid rgba(230, 57, 70, 0.1);
        margin-bottom: 30px;
        transition: var(--transition);
    }

    .card:hover {
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }

    .card h3 {
        color: var(--light-red);
        margin-top: 0;
        margin-bottom: 20px;
    }

    .subscription-details p {
        margin-bottom: 10px;
        color: rgba(255, 255, 255, 0.8);
    }

    .subscription-details strong {
        color: var(--light);
        min-width: 120px;
        display: inline-block;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.3);
        color: var(--success);
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: var(--danger);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--light);
    }

    .form-group select {
        width: 100%;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: var(--light);
        transition: var(--transition);
    }

    .form-group select:focus {
        border-color: var(--light-red);
        outline: none;
    }

    .btn {
        background: var(--red);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
    }

    .btn:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table th {
        color: var(--light-red);
        font-weight: 600;
    }

    .table tr:hover td {
        background: rgba(255, 255, 255, 0.03);
    }

    .payment-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .payment-status.paid {
        background: rgba(40, 167, 69, 0.2);
        color: var(--success);
    }

    .payment-status.pending {
        background: rgba(255, 193, 7, 0.2);
        color: var(--warning);
    }

    .payment-status.failed {
        background: rgba(220, 53, 69, 0.2);
        color: var(--danger);
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="subscription.php" class="active"><i class="fas fa-credit-card"></i> Mon Abonnement</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Mon Abonnement</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Abonnement Actuel</h3>
            
            <?php if ($current_subscription): ?>
                <div class="subscription-details">
                    <p><strong>Type:</strong> 
                        <?php echo $subscription_types[$current_subscription['type']]['name']; ?>
                    </p>
                    <p><strong>Prix:</strong> 
                        <?php echo number_format($current_subscription['price'], 0, ',', ' '); ?> DZD
                    </p>
                    <p><strong>Date de début:</strong> 
                        <?php echo date('d/m/Y', strtotime($current_subscription['start_date'])); ?>
                    </p>
                    <p><strong>Date d'expiration:</strong> 
                        <?php echo date('d/m/Y', strtotime($current_subscription['end_date'])); ?>
                    </p>
                    <p><strong>Statut:</strong> 
                        <span class="payment-status <?php echo $current_subscription['payment_status']; ?>">
                            <?php echo ucfirst($current_subscription['payment_status']); ?>
                        </span>
                    </p>
                </div>
            <?php else: ?>
                <p>Vous n'avez pas d'abonnement actif.</p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h3>Souscrire à un nouvel abonnement</h3>
            
            <form method="post">
                <div class="form-group">
                    <label for="type">Type d'abonnement</label>
                    <select id="type" name="type" required>
                        <?php foreach ($subscription_types as $key => $type): ?>
                            <option value="<?php echo $key; ?>">
                                <?php echo $type['name']; ?> - 
                                <?php echo number_format($type['price'], 0, ',', ' '); ?> DZD
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="subscribe" class="btn">Souscrire</button>
            </form>
        </div>
        
        <?php if (!empty($subscriptions)): ?>
            <div class="card">
                <h3>Historique des abonnements</h3>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscriptions as $sub): ?>
                            <tr>
                                <td><?php echo $subscription_types[$sub['type']]['name']; ?></td>
                                <td><?php echo number_format($sub['price'], 0, ',', ' '); ?> DZD</td>
                                <td><?php echo date('d/m/Y', strtotime($sub['start_date'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($sub['end_date'])); ?></td>
                                <td>
                                    <span class="payment-status <?php echo $sub['payment_status']; ?>">
                                        <?php echo ucfirst($sub['payment_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

