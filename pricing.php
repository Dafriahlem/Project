<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZoneFit - Tarifs</title>
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
        

        .pricing-section {
            padding: 6rem 2rem;
            background: var(--black);
            position: relative;
            overflow: hidden;
        }
        
        .pricing-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--black), var(--red), var(--light-red));
        }
        
        .pricing-section h2 {
            text-align: center;
            font-size: 3rem;
            color: var(--light);
            margin-bottom: 1rem;
            position: relative;
            animation: fadeInDown 1s ease;
        }
        
        .pricing-section h2 span {
            color: var(--red);
        }
        
        .pricing-section p {
            text-align: center;
            font-size: 1.2rem;
            color: #ccc;
            max-width: 700px;
            margin: 0 auto 3rem;
            animation: fadeIn 1.5s ease;
        }
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .pricing-card {
            background: var(--dark-gray);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(50px);
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
            border: 1px solid rgba(230, 57, 70, 0.1);
        }
        
        .pricing-card:nth-child(1) { animation-delay: 0.2s; }
        .pricing-card:nth-child(2) { animation-delay: 0.4s; }
        .pricing-card:nth-child(3) { animation-delay: 0.6s; }
        .pricing-card:nth-child(4) { animation-delay: 0.8s; }
        
        .pricing-card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 15px 40px rgba(230, 57, 70, 0.2);
            border-color: rgba(230, 57, 70, 0.3);
        }
        
        .pricing-card.popular {
            border: 3px solid var(--red);
            position: relative;
            z-index: 1;
        }
        
        .pricing-card.popular::after {
            content: 'POPULAIRE';
            position: absolute;
            top: 15px;
            right: -35px;
            background: var(--red);
            color: white;
            padding: 0.5rem 2.5rem;
            font-size: 0.9rem;
            font-weight: bold;
            transform: rotate(45deg);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .pricing-card h3 {
            font-size: 1.8rem;
            color: var(--light);
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--light-red);
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .price::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: var(--red);
            margin: 1rem auto;
        }
        
        .features {
            list-style: none;
            padding: 0;
            margin-bottom: 2.5rem;
        }
        
        .features li {
            padding: 0.8rem 0;
            position: relative;
            padding-left: 2rem;
            color: #ccc;
        }
        
        .features li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--red);
            position: absolute;
            left: 0;
        }
        
        .btn {
            display: block;
            text-align: center;
            padding: 1rem;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: var(--red);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }
        
        .btn-primary:hover {
            background: var(--light-red);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(230, 57, 70, 0.4);
        }
        
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-primary:hover::after {
            left: 100%;
        }
        
        .admin-note {
            max-width: 1200px;
            margin: 3rem auto 0;
            padding: 1.5rem;
            background: rgba(230, 57, 70, 0.1);
            border-left: 4px solid var(--red);
            border-radius: 0 5px 5px 0;
            animation: fadeIn 1s ease;
            color: #ccc;
        }
        
        /* Animations */
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
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        

        @media (max-width: 768px) {
            .pricing-section {
                padding: 4rem 1rem;
            }
            
            .pricing-section h2 {
                font-size: 2.2rem;
            }
            
            .pricing-card.popular::after {
                right: -25px;
                padding: 0.5rem 1.5rem;
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>

<?php include_once "./includes/header.php" ?>
    <section class="pricing-section">
        <h2>Nos <span>Tarifs</span></h2>
        <p>Choisissez l'abonnement qui correspond à vos besoins</p>
        
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>1 mois</h3>
                <div class="price">5 000 DZD</div>
                
                <ul class="features">
                    <li>Accès illimité à la salle</li>
                    <li>Accès aux équipements</li>
                    <li>1 séance avec un coach</li>
                </ul>
                
                <a href="register.php" class="btn btn-primary">S'inscrire</a>
            </div>
            
            <div class="pricing-card">
                <h3>3 mois</h3>
                <div class="price">13 500 DZD</div>
                
                <ul class="features">
                    <li>Accès illimité à la salle</li>
                    <li>Accès aux équipements</li>
                    <li>3 séances avec un coach</li>
                    <li>10% de réduction</li>
                </ul>
                
                <a href="register.php" class="btn btn-primary">S'inscrire</a>
            </div>
            
            <div class="pricing-card popular">
                <h3>6 mois</h3>
                <div class="price">24 000 DZD</div>
                
                <ul class="features">
                    <li>Accès illimité à la salle</li>
                    <li>Accès aux équipements</li>
                    <li>6 séances avec un coach</li>
                    <li>20% de réduction</li>
                    <li>Accès aux cours collectifs</li>
                </ul>
                
                <a href="register.php" class="btn btn-primary">S'inscrire</a>
            </div>
            
            <div class="pricing-card">
                <h3>12 mois</h3>
                <div class="price">42 000 DZD</div>
                
                <ul class="features">
                    <li>Accès illimité à la salle</li>
                    <li>Accès aux équipements</li>
                    <li>12 séances avec un coach</li>
                    <li>30% de réduction</li>
                    <li>Accès illimité aux cours collectifs</li>
                    <li>Évaluation gratuite</li>
                </ul>
                
                <a href="register.php" class="btn btn-primary">S'inscrire</a>
            </div>
        </div>
    </section>

    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            const pricingCards = document.querySelectorAll('.pricing-card');
            
            const animateOnScroll = () => {
                pricingCards.forEach(card => {
                    const cardPosition = card.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if (cardPosition < screenPosition) {
                        card.style.animationPlayState = 'running';
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); 
        });
    </script>
</body>
</html>