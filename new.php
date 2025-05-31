<?php
include './includes/config.php';
checkRole('client'); 


$stmt = $pdo->query("SELECT c.*, u.username as coach_name 
                     FROM challenges c
                     JOIN users u ON c.coach_id = u.id
                     ORDER BY start_date DESC");
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Défis Disponibles";
include './includes/header.php';
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

    .challenges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .challenge-card {
        background: var(--dark-gray);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(230, 57, 70, 0.1);
        transition: var(--transition);
        position: relative;
    }

    .challenge-card:hover {
        transform: translateY(-5px);
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }

    .challenge-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-active {
        background: rgba(40, 167, 69, 0.2);
        color: var(--success);
    }

    .status-upcoming {
        background: rgba(255, 193, 7, 0.2);
        color: var(--warning);
    }

    .status-ended {
        background: rgba(220, 53, 69, 0.2);
        color: var(--danger);
    }

    .challenge-card h3 {
        margin: 0 0 1rem 0;
        color: var(--light-red);
        font-size: 1.4rem;
    }

    .challenge-coach {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .challenge-description {
        margin: 1rem 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
    }

    .challenge-dates {
        display: flex;
        justify-content: space-between;
        margin: 1rem 0;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
    }

    .challenge-dates i {
        margin-right: 5px;
        color: var(--light-red);
    }

    .challenge-reward {
        background: rgba(255, 193, 7, 0.2);
        padding: 0.75rem;
        border-radius: 5px;
        margin: 1rem 0;
        color: var(--warning);
    }

    .challenge-reward i {
        margin-right: 5px;
    }

    .no-challenges {
        text-align: center;
        padding: 3rem;
        color: rgba(255, 255, 255, 0.5);
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="challenges.php" class="active"><i class="fas fa-trophy"></i> Défis</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Défis Disponibles</h2>
        
        <?php if (empty($challenges)): ?>
            <div class="no-challenges">
                <p>Aucun défi disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="challenges-grid">
                <?php foreach ($challenges as $challenge): 
                    $current_date = new DateTime();
                    $start_date = new DateTime($challenge['start_date']);
                    $end_date = new DateTime($challenge['end_date']);
                    
                    if ($current_date > $end_date) {
                        $status = 'ended';
                        $status_text = 'Terminé';
                    } elseif ($current_date >= $start_date && $current_date <= $end_date) {
                        $status = 'active';
                        $status_text = 'Actif';
                    } else {
                        $status = 'upcoming';
                        $status_text = 'À venir';
                    }
                ?>
                    <div class="challenge-card">
                        <span class="challenge-status status-<?= $status ?>"><?= $status_text ?></span>
                        <h3><?= htmlspecialchars($challenge['title']) ?></h3>
                        <p class="challenge-coach">Par <?= htmlspecialchars($challenge['coach_name']) ?></p>
                        
                        <div class="challenge-description">
                            <?= nl2br(htmlspecialchars($challenge['description'])) ?>
                        </div>
                        
                        <div class="challenge-dates">
                            <span><i class="fas fa-calendar-day"></i> Début: <?= date('d/m/Y', strtotime($challenge['start_date'])) ?></span>
                            <span><i class="fas fa-calendar-check"></i> Fin: <?= date('d/m/Y', strtotime($challenge['end_date'])) ?></span>
                        </div>
                        
                        <?php if (!empty($challenge['reward'])): ?>
                            <div class="challenge-reward">
                                <i class="fas fa-gift"></i> Récompense: <?= htmlspecialchars($challenge['reward']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

