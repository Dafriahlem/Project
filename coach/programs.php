<?php
include '../includes/config.php';
checkRole('coach');

$coach_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_program'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $difficulty = in_array($_POST['difficulty'], ['débutant', 'intermédiaire', 'avancé']) ? $_POST['difficulty'] : 'débutant';
    
    $stmt = $pdo->prepare("INSERT INTO programs (coach_id, title, description, duration, difficulty) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$coach_id, $title, $description, $duration, $difficulty]);
    
    $_SESSION['success_message'] = "Programme ajouté avec succès";
    header("Location: programs.php");
    exit();
}


if (isset($_GET['delete'])) {
    $program_id = intval($_GET['delete']);
    

    $stmt = $pdo->prepare("SELECT id FROM programs WHERE id = ? AND coach_id = ?");
    $stmt->execute([$program_id, $coach_id]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
        $stmt->execute([$program_id]);
        
        $_SESSION['success_message'] = "Programme supprimé avec succès";
    } else {
        $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer ce programme";
    }
    
    header("Location: programs.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM programs WHERE coach_id = ? ORDER BY created_at DESC");
$stmt->execute([$coach_id]);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Gestion des Programmes";
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
        background: #dc3545;
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
        color: #28a745;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #dc3545;
    }

    .programs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .program-card {
        background: var(--dark-gray);
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid rgba(230, 57, 70, 0.1);
        transition: var(--transition);
    }
    
    .program-card:hover {
        transform: translateY(-5px);
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }
    
    .program-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .program-card h4 {
        margin: 0;
        color: var(--light-red);
    }
    
    .difficulty {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .difficulty-débutant {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }
    
    .difficulty-intermédiaire {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }
    
    .difficulty-avancé {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
    
    .program-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .program-meta span {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .program-meta i {
        margin-right: 5px;
        color: var(--light-red);
    }
    
    .actions {
        display: flex;
        gap: 0.5rem;
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a></li>
            <li><a href="exercises.php"><i class="fas fa-running"></i> Exercices</a></li>
            <li><a href="programs.php" class="active"><i class="fas fa-project-diagram"></i> Programmes</a></li>
            <li><a href="challenges.php"><i class="fas fa-trophy"></i> Défis</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Gestion des Programmes</h2>
        
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
        
        <button id="toggleProgramForm" class="btn">Ajouter un programme</button>
        
        <div id="programForm" style="display: none;">
            <form method="post" class="card">
                <h3>Nouveau programme</h3>
                
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
                        <label for="duration">Durée (jours)</label>
                        <input type="number" id="duration" name="duration" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="difficulty">Difficulté</label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="débutant">Débutant</option>
                            <option value="intermédiaire">Intermédiaire</option>
                            <option value="avancé">Avancé</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" name="add_program" class="btn">Enregistrer</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Liste des programmes</h3>
            
            <?php if (empty($programs)): ?>
                <p>Aucun programme trouvé.</p>
            <?php else: ?>
                <div class="programs-grid">
                    <?php foreach ($programs as $program): ?>
                        <div class="program-card">
                            <div class="program-header">
                                <h4><?= htmlspecialchars($program['title']) ?></h4>
                                <span class="difficulty difficulty-<?= $program['difficulty'] ?>">
                                    <?= ucfirst($program['difficulty']) ?>
                                </span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($program['description'])) ?></p>
                            <div class="program-meta">
                                <span><i class="fas fa-calendar-alt"></i> <?= $program['duration'] ?> jours</span>
                                <div class="actions">
                                    <a href="edit_program.php?id=<?= $program['id'] ?>" class="btn btn-sm">Modifier</a>
                                    <a href="programs.php?delete=<?= $program['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce programme?')">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleProgramForm').addEventListener('click', function() {
        const form = document.getElementById('programForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
</script>

