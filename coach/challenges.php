<?php
include '../includes/config.php';
checkRole('coach');

$coach_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_challenge'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reward = trim($_POST['reward']);
    
 
    if (strtotime($start_date) > strtotime($end_date)) {
        $_SESSION['error_message'] = "La date de fin doit être après la date de début";
    } else {
        $stmt = $pdo->prepare("INSERT INTO challenges (coach_id, title, description, start_date, end_date, reward) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$coach_id, $title, $description, $start_date, $end_date, $reward]);
        
        $_SESSION['success_message'] = "Défi ajouté avec succès";
    }
    
    header("Location: challenges.php");
    exit();
}


if (isset($_GET['delete'])) {
    $challenge_id = intval($_GET['delete']);
    
    
    $stmt = $pdo->prepare("SELECT id FROM challenges WHERE id = ? AND coach_id = ?");
    $stmt->execute([$challenge_id, $coach_id]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM challenges WHERE id = ?");
        $stmt->execute([$challenge_id]);
        
        $_SESSION['success_message'] = "Défi supprimé avec succès";
    } else {
        $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer ce défi";
    }
    
    header("Location: challenges.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM challenges WHERE coach_id = ? ORDER BY start_date DESC");
$stmt->execute([$coach_id]);
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Gestion des Défis";
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
        margin-bottom: 20px;
    }

    .card h3 {
        color: var(--light-red);
        margin-top: 0;
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
    }

    .btn:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.9rem;
    }

    .btn-danger {
        background: var(--danger);
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: var(--light);
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: var(--light);
    }

    .form-group textarea {
        min-height: 100px;
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
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

    .challenges-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .challenge-card {
        background: var(--dark-gray);
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid rgba(230, 57, 70, 0.1);
        border-left: 5px solid;
        transition: var(--transition);
    }
    
    .challenge-card:hover {
        transform: translateY(-5px);
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }
    
    .challenge-card.active {
        border-left-color: var(--success);
    }
    
    .challenge-card.expired {
        border-left-color: var(--danger);
    }
    
    .challenge-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .challenge-card h4 {
        margin: 0;
        color: var(--light-red);
    }
    
    .status {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .challenge-card.active .status {
        background: rgba(40, 167, 69, 0.2);
        color: var(--success);
    }
    
    .challenge-card.expired .status {
        background: rgba(220, 53, 69, 0.2);
        color: var(--danger);
    }
    
    .challenge-dates {
        display: flex;
        justify-content: space-between;
        margin: 1rem 0;
        color: rgba(255, 255, 255, 0.6);
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
    
    .challenge-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a></li>
            <li><a href="exercises.php"><i class="fas fa-running"></i> Exercices</a></li>
            <li><a href="programs.php"><i class="fas fa-project-diagram"></i> Programmes</a></li>
            <li><a href="challenges.php" class="active"><i class="fas fa-trophy"></i> Défis</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Gestion des Défis</h2>
        
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
        
        <button id="toggleChallengeForm" class="btn">Créer un défi</button>
        
        <div id="challengeForm" style="display: none;">
            <form method="post" class="card">
                <h3>Nouveau défi</h3>
                
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Date de début</label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date">Date de fin</label>
                        <input type="date" id="end_date" name="end_date" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reward">Récompense</label>
                    <input type="text" id="reward" name="reward" placeholder="Ex: 1 mois gratuit, T-shirt, etc.">
                </div>
                
                <button type="submit" name="add_challenge" class="btn">Créer le défi</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Liste des défis</h3>
            
            <?php if (empty($challenges)): ?>
                <p>Aucun défi trouvé.</p>
            <?php else: ?>
                <div class="challenges-list">
                    <?php foreach ($challenges as $challenge): ?>
                        <div class="challenge-card <?= strtotime($challenge['end_date']) < time() ? 'expired' : 'active' ?>">
                            <div class="challenge-header">
                                <h4><?= htmlspecialchars($challenge['title']) ?></h4>
                                <span class="status">
                                    <?= strtotime($challenge['end_date']) < time() ? 'Terminé' : 'Actif' ?>
                                </span>
                            </div>
                            
                            <p><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>
                            
                            <div class="challenge-dates">
                                <span><i class="fas fa-calendar-day"></i> Début: <?= date('d/m/Y', strtotime($challenge['start_date'])) ?></span>
                                <span><i class="fas fa-calendar-check"></i> Fin: <?= date('d/m/Y', strtotime($challenge['end_date'])) ?></span>
                            </div>
                            
                            <?php if (!empty($challenge['reward'])): ?>
                                <div class="challenge-reward">
                                    <i class="fas fa-gift"></i> Récompense: <?= htmlspecialchars($challenge['reward']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="challenge-actions">
                                <a href="challenges.php?delete=<?= $challenge['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce défi?')">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleChallengeForm').addEventListener('click', function() {
        const form = document.getElementById('challengeForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
    
  
    document.getElementById('start_date').min = new Date().toISOString().split('T')[0];
    

    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
</script>

