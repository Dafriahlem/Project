<?php
include '../includes/config.php';
checkRole('coach');

$coach_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_exercise'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $video_url = trim($_POST['video_url']);
    $category = trim($_POST['category']);

    $stmt = $pdo->prepare("INSERT INTO exercises (coach_id, title, description, video_url, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$coach_id, $title, $description, $video_url, $category]);

    $_SESSION['success_message'] = "Exercice ajouté avec succès";
    redirect('exercises.php');
}


if (isset($_GET['delete'])) {
    $exercise_id = $_GET['delete'];

    $stmt = $pdo->prepare("SELECT id FROM exercises WHERE id = ? AND coach_id = ?");
    $stmt->execute([$exercise_id, $coach_id]);

    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
        $stmt->execute([$exercise_id]);

        $_SESSION['success_message'] = "Exercice supprimé avec succès";
    } else {
        $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer cet exercice";
    }

    redirect('exercises.php');
}


$stmt = $pdo->prepare("SELECT * FROM exercises WHERE coach_id = ? ORDER BY created_at DESC");
$stmt->execute([$coach_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Gestion des Exercices";
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
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="exercises.php" class="active"><i class="fas fa-running"></i> Exercices</a></li>
            <li><a href="programs.php"><i class="fas fa-project-diagram"></i> Programmes</a></li>
            <li><a href="challenges.php"><i class="fas fa-trophy"></i> Défis</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Gestion des Exercices</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message'];
                unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <button id="toggleExerciseForm" class="btn">Ajouter un exercice</button>

        <div id="exerciseForm" style="display: none;">
            <form method="post" class="card">
                <h3>Nouvel exercice</h3>

                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="video_url">URL de la vidéo</label>
                    <input type="url" id="video_url" name="video_url">
                </div>

                <div class="form-group">
    <label for="category">Catégorie</label>
    <select id="category" name="category" required>
        <option value="">-- Sélectionner une catégorie --</option>
        <option value="Cardio">Cardio</option>
        <option value="Yoga">Yoga</option>
        <option value="Musculation">Musculation</option>
        <option value="Hiit">Hiit</option>
        <option value="Fitness">Fitness</option>
    </select>
</div>


                <button type="submit" name="add_exercise" class="btn">Enregistrer</button>
            </form>
        </div>

        <div class="card">
            <h3>Liste des exercices</h3>

            <?php if (empty($exercises)): ?>
                <p>Aucun exercice trouvé.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exercises as $exercise): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($exercise['title']); ?></td>
                                <td><?php echo htmlspecialchars($exercise['category']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($exercise['created_at'])); ?></td>
                                <td>
                                    <a href="edit_exercise.php?id=<?php echo $exercise['id']; ?>" class="btn btn-sm">Modifier</a>
                                    <a href="exercises.php?delete=<?php echo $exercise['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleExerciseForm').addEventListener('click', function() {
        const form = document.getElementById('exerciseForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
</script>