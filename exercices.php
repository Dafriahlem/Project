<?php



include 'includes/config.php';
$page_title = "Exercices Fitness";
include 'includes/header.php';


$exercises = [
    'cardio' => [
        [
            'id' => 1,
            'title' => "Course à Pied",
            'image_url' => "./assets/images/1.avif",
            'description' => "Améliorez votre endurance cardiovasculaire avec des sessions de 30 minutes.",
            'duration' => "30 min",
            'difficulty' => "Débutant",
            'coach_name' => "Coach Jean"
        ],
        [
            'id' => 2,
            'title' => "Corde à Sauter",
            'image_url' => "./assets/images/2.avif",
            'description' => "Excellent pour la coordination et le système cardiovasculaire.",
            'duration' => "15-20 min",
            'difficulty' => "Intermédiaire",
            'coach_name' => "Coach Marie"
        ]
    ],
    'musculation' => [
        [
            'id' => 3,
            'title' => "Squats",
            'image_url' => "./assets/images/3.avif",
            'description' => "Renforcez vos jambes et vos fessiers avec cet exercice fondamental.",
            'duration' => "4x12 répétitions",
            'difficulty' => "Débutant",
            'coach_name' => "Coach Pierre"
        ],
        [
            'id' => 4,
            'title' => "Développé Couché",
            'image_url' => "./assets/images/4.avif",
            'description' => "Exercice complet pour le haut du corps, particulièrement les pectoraux.",
            'duration' => "4x10 répétitions",
            'difficulty' => "Intermédiaire",
            'coach_name' => "Coach Luc"
        ]
    ],
    'yoga' => [
        [
            'id' => 5,
            'title' => "Posture du Chien Tête en Bas",
            'image_url' => "./assets/images/5.avif",
            'description' => "Étire tout le corps et améliore la circulation sanguine.",
            'duration' => "3-5 respirations",
            'difficulty' => "Débutant",
            'coach_name' => "Coach Sophie"
        ],
        [
            'id' => 6,
            'title' => "Posture du Guerrier",
            'image_url' => "./assets/images/6.avif",
            'description' => "Renforce les jambes et améliore l'équilibre.",
            'duration' => "30 secondes par côté",
            'difficulty' => "Intermédiaire",
            'coach_name' => "Coach Yann"
        ]
    ],
    '30days' => [
        [
            'id' => 7,
            'title' => "Défi 30 Jours - Jour 1",
            'image_url' => "./assets/images/day1.avif",
            'description' => "Échauffement complet et exercices de base pour commencer votre parcours.",
            'duration' => "20 min",
            'difficulty' => "Débutant",
            'coach_name' => "Programme 30J"
        ],
        [
            'id' => 8,
            'title' => "Défi 30 Jours - Jour 7",
            'image_url' => "./assets/images/day7.avif",
            'description' => "Première semaine complète! Intensité légèrement augmentée.",
            'duration' => "25 min",
            'difficulty' => "Débutant",
            'coach_name' => "Programme 30J"
        ],
        [
            'id' => 9,
            'title' => "Défi 30 Jours - Jour 15",
            'image_url' => "./assets/images/day15.avif",
            'description' => "Mi-parcours! Routine complète pour tout le corps.",
            'duration' => "35 min",
            'difficulty' => "Intermédiaire",
            'coach_name' => "Programme 30J"
        ],
        [
            'id' => 10,
            'title' => "Défi 30 Jours - Jour 30",
            'image_url' => "./assets/images/day30.avif",
            'description' => "Dernier jour! Célébrez votre réussite avec cette séance spéciale.",
            'duration' => "45 min",
            'difficulty' => "Intermédiaire",
            'coach_name' => "Programme 30J"
        ]
    ]
];


if ($conn) {
    try {
       
        $stmt = $conn->query("SELECT id, coach_id, title, description, duration, difficulty, category, image_url FROM exercises");
        $dbExercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
       
        $stmt = $conn->query("SELECT id, coach_id, title, description, duration, difficulty, '30days' as category FROM programs WHERE title LIKE '%30 jours%'");
        $dbPrograms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($dbExercises || $dbPrograms) {
            $exercises = [
                'cardio' => [],
                'musculation' => [],
                'yoga' => [],
                '30days' => []
            ];
            
            foreach ($dbExercises as $exercise) {
                $exercises[$exercise['category']][] = $exercise;
            }
            
            foreach ($dbPrograms as $program) {
                $exercises['30days'][] = $program;
            }
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2A2A72;
            --secondary: #009FFD;
            --accent: #FFA400;
            --light: #F8F9FA;
            --dark: #232528;
            --success: #28a745;
            --danger: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--dark);
        }
        
        .exercises-section {
            padding: 6rem 2rem;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeInDown 1s ease;
        }
        
        .section-header h2 {
            font-size: 3rem;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .section-header p {
            font-size: 1.2rem;
            color: var(--dark);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .categories {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .category-btn {
            padding: 0.8rem 1.8rem;
            background: white;
            border: 2px solid var(--primary);
            border-radius: 50px;
            font-weight: bold;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .category-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .category-btn:hover::after {
            left: 100%;
        }
        
        .category-btn:hover, .category-btn.active {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(42, 42, 114, 0.2);
        }
        
        .exercises-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .exercises-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .exercise-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(50px);
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
        }
        
        .exercise-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.7));
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .exercise-card:hover::before {
            opacity: 1;
        }
        
        .exercise-card:nth-child(1) { animation-delay: 0.1s; }
        .exercise-card:nth-child(2) { animation-delay: 0.2s; }
        .exercise-card:nth-child(3) { animation-delay: 0.3s; }
        .exercise-card:nth-child(4) { animation-delay: 0.4s; }
        .exercise-card:nth-child(5) { animation-delay: 0.5s; }
        .exercise-card:nth-child(6) { animation-delay: 0.6s; }
        .exercise-card:nth-child(7) { animation-delay: 0.7s; }
        .exercise-card:nth-child(8) { animation-delay: 0.8s; }
        
        .exercise-card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .exercise-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }
        
        .exercise-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .exercise-card:hover .exercise-image img {
            transform: scale(1.1);
        }
        
        .difficulty {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 164, 0, 0.9);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: bold;
            z-index: 2;
        }
        
        .difficulty.beginner {
            background: rgba(40, 167, 69, 0.9);
        }
        
        .difficulty.intermediate {
            background: rgba(255, 164, 0, 0.9);
        }
        
        .difficulty.advanced {
            background: rgba(220, 53, 69, 0.9);
        }
        
        .badge-30days {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(0, 159, 253, 0.9);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: bold;
            z-index: 2;
        }
        
        .exercise-content {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .exercise-content h3 {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .exercise-card:hover .exercise-content h3 {
            color: var(--secondary);
        }
        
        .exercise-content p {
            color: var(--dark);
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .exercise-meta {
            display: flex;
            justify-content: space-between;
            color: var(--secondary);
            font-weight: bold;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .coach-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-weight: 600;
        }
        
        .coach-name i {
            color: var(--accent);
        }
        
        .floating-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--accent);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(255, 164, 0, 0.4);
            z-index: 100;
            cursor: pointer;
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
        }
        
        .floating-btn:hover {
            transform: scale(1.1) translateY(-5px);
            animation: none;
            box-shadow: 0 8px 25px rgba(255, 164, 0, 0.6);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
            .exercises-section {
                padding: 4rem 1rem;
            }
            
            .section-header h2 {
                font-size: 2.2rem;
            }
            
            .exercises-grid {
                grid-template-columns: 1fr;
            }
            
            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                bottom: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body>
    <section class="exercises-section">
        <div class="section-header">
            <h2>Nos Exercices</h2>
            <p>Découvrez notre collection complète d'exercices pour atteindre vos objectifs fitness</p>
        </div>
        
        <div class="categories">
            <button class="category-btn active" data-category="all">Tous</button>
            <button class="category-btn" data-category="cardio">Cardio</button>
            <button class="category-btn" data-category="musculation">Musculation</button>
            <button class="category-btn" data-category="yoga">Yoga</button>
            <button class="category-btn" data-category="30days">Défi 30 Jours</button>
        </div>
        
        <div class="exercises-container">
            <div class="exercises-grid">
                <?php foreach ($exercises as $category => $category_exercises): ?>
                    <?php foreach ($category_exercises as $exercise): ?>
                        <div class="exercise-card" data-category="<?= htmlspecialchars($category) ?>">
                            <div class="exercise-image">
                                <img src="<?= htmlspecialchars($exercise['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($exercise['title']) ?>" 
                                     loading="lazy">
                                <span class="difficulty <?= strtolower($exercise['difficulty']) ?>">
                                    <?= htmlspecialchars($exercise['difficulty']) ?>
                                </span>
                                <?php if ($category === '30days'): ?>
                                    <span class="badge-30days">30 Jours</span>
                                <?php endif; ?>
                            </div>
                            <div class="exercise-content">
                                <h3><?= htmlspecialchars($exercise['title']) ?></h3>
                                <p><?= htmlspecialchars($exercise['description']) ?></p>
                                <div class="exercise-meta">
                                    <span><i class="fas fa-clock"></i> <?= htmlspecialchars($exercise['duration']) ?></span>
                                    <span><?= ucfirst(htmlspecialchars($category)) ?></span>
                                </div>
                                <?php if (!empty($exercise['coach_name'])): ?>
                                    <div class="coach-name">
                                        <i class="fas fa-user-tie"></i>
                                        <span><?= htmlspecialchars($exercise['coach_name']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <a href="#" class="floating-btn" id="start-challenge">
        <i class="fas fa-fire"></i>
    </a>

    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
           
            const categoryButtons = document.querySelectorAll('.category-btn');
            
            categoryButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                
                    categoryButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const category = this.dataset.category;
                    const cards = document.querySelectorAll('.exercise-card');
                    
                    
                    cards.forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'block';
                        
                            const img = card.querySelector('img');
                            if (img.complete) {
                                animateCard(card);
                            } else {
                                img.addEventListener('load', () => animateCard(card));
                                img.addEventListener('error', () => {
                                    img.src = './assets/images/default-exercise.jpg';
                                    animateCard(card);
                                });
                            }
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                 
                    document.querySelector('.exercises-grid').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });
            
            function animateCard(card) {
                card.style.animation = 'none';
                void card.offsetWidth; 
                card.style.animation = 'fadeInUp 0.6s ease forwards';
            }
            
    
            const challengeBtn = document.getElementById('start-challenge');
            challengeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                
                document.querySelector('.category-btn[data-category="30days"]').click();
                
              
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.style.backgroundColor = 'var(--success)';
                this.style.boxShadow = '0 5px 20px rgba(40, 167, 69, 0.4)';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-fire"></i>';
                    this.style.backgroundColor = 'var(--accent)';
                    this.style.boxShadow = '0 5px 20px rgba(255, 164, 0, 0.4)';
                }, 2000);
            });
            
        
            tippy('#start-challenge', {
                content: 'Commencer le défi 30 jours!',
                placement: 'left',
                animation: 'scale',
                theme: 'light',
            });
            
          
            const animateOnScroll = () => {
                document.querySelectorAll('.exercise-card').forEach(card => {
                    const rect = card.getBoundingClientRect();
                    if (rect.top < window.innerHeight * 0.75) {
                        card.style.animationPlayState = 'running';
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); 
            
           
            document.querySelectorAll('.exercise-image img').forEach(img => {
                img.addEventListener('error', function() {
                    this.src = './assets/images/default-exercise.jpg';
                });
            });
        });
    </script>
</body>
</html>