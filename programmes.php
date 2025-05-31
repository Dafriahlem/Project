<?php
include './includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
    header("Location: dashboard.php");
    exit();
}

$client_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND end_date >= CURDATE()");
$stmt->execute([$client_id]);
$subscription = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subscription) {
    header("Location: subscribe.php?required=1");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM exercises ORDER BY created_at DESC");
$stmt->execute();
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Mes Exercices";
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

    .exercises-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .exercise-card {
        background: var(--dark-gray);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(230, 57, 70, 0.1);
        transition: var(--transition);
    }

    .exercise-card:hover {
        transform: translateY(-5px);
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }

    .exercise-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .exercise-card h3 {
        margin: 0;
        color: var(--light-red);
    }

    .exercise-category {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        background: rgba(230, 57, 70, 0.2);
        color: var(--light-red);
    }

    .exercise-description {
        margin: 1rem 0;
        color: rgba(255, 255, 255, 0.8);
    }

    .exercise-video {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        margin: 1rem 0;
        border-radius: 8px;
    }

    .exercise-video iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .exercise-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
    }

    .btn {
        background: var(--red);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }

    .btn:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #dc3545;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .no-exercises {
        text-align: center;
        padding: 2rem;
        color: rgba(255, 255, 255, 0.5);
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a></li>
            <li><a href="exercises.php" class="active"><i class="fas fa-running"></i> Exercices</a></li>
            <li><a href="programs.php"><i class="fas fa-project-diagram"></i> Programmes</a></li>
            <li><a href="challenges.php"><i class="fas fa-trophy"></i> Défis</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Mes Exercices</h2>
        
        <?php if (empty($exercises)): ?>
            <div class="no-exercises">
                <p>Aucun exercice trouvé. Commencez par créer votre premier exercice.</p>
                <a href="add_exercise.php" class="btn">Ajouter un exercice</a>
            </div>
        <?php else: ?>
            <div class="exercises-grid">
                <?php foreach ($exercises as $exercise): ?>
                    <div class="exercise-card">
                        <div class="exercise-header">
                            <h3><?= htmlspecialchars($exercise['title']) ?></h3>
                            <span class="exercise-category"><?= htmlspecialchars($exercise['category']) ?></span>
                        </div>
                        
                        <div class="exercise-description">
                            <?= nl2br(htmlspecialchars($exercise['description'])) ?>
                        </div>
                        
                        <?php if (!empty($exercise['video_url'])): ?>
                            <div class="exercise-video">
                                <?php
                                $video_url = $exercise['video_url'];
                                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                    parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
                                    $video_id = isset($params['v']) ? $params['v'] : substr(parse_url($video_url, PHP_URL_PATH), 1);
                                    $video_url = "https://www.youtube.com/embed/$video_id";
                                }
                                ?>
                                <iframe src="<?= $video_url ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php endif; ?>
                        
                        <div class="exercise-meta">
                            <span>Créé le <?= date('d/m/Y', strtotime($exercise['created_at'])) ?></span>
                            <!-- <div>
                                <a href="delete_exercise.php?id=<?= $exercise['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice?')">Supprimer</a>
                            </div> -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
