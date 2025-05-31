<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZoneFit - Fitness Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --black: #0a0a0a;
            --dark-gray: #1a1a1a;
            --red: #e63946;
            --light-red: #ff4d5a;
            --light: #f8f9fa;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--black);
            color: var(--light);
            line-height: 1.6;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(230, 57, 70, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(230, 57, 70, 0.1) 0%, transparent 20%);
            min-height: 100vh;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
       
        header {
            background-color: rgba(10, 10, 10, 0.95);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.1);
            border-bottom: 1px solid rgba(230, 57, 70, 0.2);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            text-decoration: none;
            color: var(--light);
            letter-spacing: -1px;
            display: flex;
            align-items: center;
        }
        
        .logo span {
            color: var(--red);
            margin-left: 4px;
        }
        
        .logo:hover span {
            color: var(--light-red);
        }
        
  
        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }
        
        nav ul li a {
            color: var(--light);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
            padding: 5px 0;
            transition: var(--transition);
        }
        
        nav ul li a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--red);
            transition: var(--transition);
        }
        
        nav ul li a:hover {
            color: var(--light-red);
        }
        
        nav ul li a:hover:after {
            width: 100%;
        }
        
       
        .mobile-toggle {
            display: none;
            background: transparent;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
        }
        
        
        .hero {
            height: 85vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(10, 10, 10, 0.9) 0%, rgba(10, 10, 10, 0.6) 100%);
            z-index: -1;
        }
        
        .hero-content {
            max-width: 650px;
        }
        
        .hero h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(to right, var(--light), var(--light-red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(230, 57, 70, 0.2);
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            color: #ccc;
        }
        
        .btn {
            display: inline-block;
            background: var(--red);
            color: white;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }
        
        .btn:hover {
            background: var(--light-red);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(230, 57, 70, 0.4);
        }
        
      
        .features {
            padding: 100px 0;
            background: var(--dark-gray);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 2.8rem;
        }
        
        .section-title span {
            color: var(--red);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: rgba(30, 30, 30, 0.7);
            border-radius: 15px;
            padding: 35px 30px;
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(230, 57, 70, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(230, 57, 70, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--red);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
      
        .testimonials {
            padding: 100px 0;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background: rgba(30, 30, 30, 0.7);
            border-radius: 15px;
            padding: 30px;
            position: relative;
            border: 1px solid rgba(230, 57, 70, 0.1);
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 5rem;
            color: var(--red);
            opacity: 0.2;
            font-family: Georgia, serif;
            line-height: 1;
        }
        
        .testimonial-content {
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        
        .author-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--red);
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
      
        footer {
            background: #0a0a0a;
            padding: 60px 0 30px;
            border-top: 1px solid rgba(230, 57, 70, 0.1);
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-col h3 {
            color: var(--red);
            margin-bottom: 25px;
            font-size: 1.4rem;
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col ul li {
            margin-bottom: 12px;
        }
        
        .footer-col ul li a {
            color: #ccc;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-col ul li a:hover {
            color: var(--light-red);
            padding-left: 5px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(230, 57, 70, 0.1);
            color: var(--light);
            border-radius: 50%;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background: var(--red);
            transform: translateY(-5px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #777;
            font-size: 0.9rem;
        }
        
     
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 3.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            
            nav {
                position: fixed;
                top: 80px;
                right: -100%;
                background: var(--dark-gray);
                width: 280px;
                height: calc(100vh - 80px);
                transition: var(--transition);
                z-index: 100;
                border-left: 1px solid rgba(230, 57, 70, 0.2);
            }
            
            nav.active {
                right: 0;
            }
            
            nav ul {
                flex-direction: column;
                padding: 30px;
                gap: 15px;
            }
            
            .hero {
                height: auto;
                padding: 100px 0;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .btn {
                padding: 12px 25px;
                font-size: 1rem;
            }
        }
        
       
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>

<?php include_once "./includes/header.php" ?>
    
    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h1>Transformez votre corps, <br>Libérez votre esprit</h1>
                <p>Découvrez une nouvelle approche du fitness avec des programmes personnalisés, un suivi expert et des résultats garantis.</p>
                <a href="register.php" class="btn pulse">Commencer maintenant</a>
            </div>
        </section>
        
        <section class="features">
            <h2 class="section-title">Pourquoi choisir <span>ZoneFit</span></h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Programmes Personnalisés</h3>
                    <p>Des entraînements adaptés à vos objectifs et à votre niveau, conçus par des experts.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Suivi de Progrès</h3>
                    <p>Visualisez vos résultats avec des statistiques détaillées et des rapports réguliers.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Communauté Active</h3>
                    <p>Rejoignez une communauté motivée et partagez vos réussites avec d'autres membres.</p>
                </div>
            </div>
        </section>
        
        <section class="testimonials">
            <h2 class="section-title">Ils nous <span>font confiance</span></h2>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Grâce à ZoneFit, j'ai perdu 12 kilos en 4 mois. Les programmes sont variés et les coachs toujours disponibles pour répondre à mes questions."</p>
                        <div class="testimonial-author">
                            <div class="author-img">M</div>
                            <div>
                                <strong>Marie Dubois</strong>
                                <p>Membre depuis 8 mois</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"L'application est intuitive et les exercices bien expliqués. J'ai enfin réussi à me construire une routine sportive régulière grâce à ZoneFit."</p>
                        <div class="testimonial-author">
                            <div class="author-img">T</div>
                            <div>
                                <strong>Thomas Martin</strong>
                                <p>Membre depuis 1 an</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Les tarifs sont très compétitifs pour la qualité du service. J'ai essayé plusieurs salles avant, mais ZoneFit est vraiment au-dessus."</p>
                        <div class="testimonial-author">
                            <div class="author-img">S</div>
                            <div>
                                <strong>Sophie Leroy</strong>
                                <p>Membre depuis 6 mois</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="index.php" class="logo">Zone<span>Fit</span></a>
                    <p>Votre partenaire fitness pour transformer votre corps et votre esprit.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Liens Rapides</h3>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="exercices.php">Nos Exercices</a></li>
                        <li><a href="pricing.php">Tarifs</a></li>
                        <li><a href="login.php">Connexion</a></li>
                        <li><a href="register.php">Inscription</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Rue du Fitness, Paris</li>
                        <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                        <li><i class="fas fa-envelope"></i> contact@zonefit.fr</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 ZoneFit. Tous droits réservés.
            </div>
        </div>
    </footer>
    
    <script>
       
        const mobileToggle = document.querySelector('.mobile-toggle');
        const nav = document.querySelector('nav');
        
        mobileToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
        });
        
      
        document.addEventListener('click', (e) => {
            if (!nav.contains(e.target) && !mobileToggle.contains(e.target) && nav.classList.contains('active')) {
                nav.classList.remove('active');
            }
        });
    </script>
</body>
</html>