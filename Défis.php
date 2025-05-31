<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Défis - ZoneFit</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap');

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #000;
      color: #fff;
      line-height: 1.6;
      scroll-behavior: smooth;
      overflow-x: hidden;
    }

    .hero {
      position: relative;
      height: 60vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: white;
      text-align: center;
      padding: 0 2rem;
      overflow: hidden;
      background: url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1?auto=format&fit=crop&w=1470&q=80') center center/cover no-repeat fixed;
      filter: brightness(0.4);
    }

    .hero h2 {
      font-size: 3rem;
      margin-bottom: 0.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.9);
      background: linear-gradient(270deg, #cc0000, #ff1a1a, #cc0000);
      background-size: 600% 600%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: gradientShift 8s ease infinite, fadeSlideIn 1.2s ease forwards 0.5s;
      opacity: 0;
      transform: translateY(30px);
    }

    .hero p {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      font-weight: 300;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.8);
      animation: fadeSlideIn 1s ease forwards 1s;
      opacity: 0;
      transform: translateY(30px);
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-auto-rows: 300px;
      gap: 15px;
      padding: 3rem 2rem;
    }

    .gallery img {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(204, 0, 0, 0.7);
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery img:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 30px rgba(255, 26, 26, 0.9);
    }

    footer {
      text-align: center;
      background: #000;
      color: #ccc;
      padding: 2rem 1rem;
      font-size: 0.95rem;
      border-top: 2px solid #cc0000;
      animation: fadeSlideIn 1s ease forwards 2s;
      opacity: 0;
      transform: translateY(30px);
    }

    footer a {
      color: #cc0000;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    footer a:hover {
      color: #ff1a1a;
      text-decoration: underline;
    }

    @keyframes fadeSlideIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes gradientShift {
      0%, 100% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
    }

    @media(max-width:768px) {
      .gallery {
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 225px;
      }
    }

  
    #lightbox {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    #lightbox img {
      max-width: 90%;
      max-height: 90%;
      border-radius: 10px;
      box-shadow: 0 0 25px rgba(255, 0, 0, 0.8);
    }
  </style>
</head>
<body>
<?php require_once "./includes/header.php" ?>
  <section class="hero" id="hero" aria-label="Présentation des défis">
    <h2>Défis d'entraînement</h2>
    <p>Relevez nos défis pour améliorer votre forme physique et votre motivation.</p>
  </section>

  <section class="gallery" aria-label="Galerie des photos de défis">
    <img src="Défis/exercice1.jpg" alt="Défi photo 1" />
    <img src="Défis/exercice2.jpg" alt="Défi photo 2" />
    <img src="Défis/exercice3.jpg" alt="Défi photo 3" />
    <img src="Défis/exercice4.jpg" alt="Défi photo 4" />
    <img src="Défis/exercice5.jpg" alt="Défi photo 5" />
    <img src="Défis/exercice6.jpg" alt="Défi photo 6" />
    <img src="Défis/exercice7.jpg" alt="Défi photo 7" />
    <img src="Défis/exercice8.jpg" alt="Défi photo 8" />
    <img src="Défis/exercice9.jpg" alt="Défi photo 9" />
    <img src="Défis/exercice10.jpg" alt="Défi photo 10" />
    <img src="Défis/exercice11.jpg" alt="Défi photo 11" />
    <img src="Défis/exercice12.jpg" alt="Défi photo 12" />
    <img src="Défis/exercice13.jpg" alt="Défi photo 13" />
    <img src="Défis/exercice14.jpg" alt="Défi photo 14" />
    <img src="Défis/exercice15.jpg" alt="Défi photo 15" />
    <img src="Défis/exercice16.jpg" alt="Défi photo 16" />
    <img src="Défis/exercice17.jpg" alt="Défi photo 17" />
    <img src="Défis/exercice18.jpg" alt="Défi photo 18" />
    <img src="Défis/exercice19.jpg" alt="Défi photo 19" />
    <img src="Défis/exercice20.jpg" alt="Défi photo 20" />
  </section>


  <div id="lightbox">
    <img id="lightbox-img" src="" alt="Image en grand" />
  </div>

  <script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');

    document.querySelectorAll('.gallery img').forEach(img => {
      img.addEventListener('click', () => {
        lightbox.style.display = 'flex';
        lightboxImg.src = img.src;
      });
    });

    lightbox.addEventListener('click', () => {
      lightbox.style.display = 'none';
    });
  </script>
</body>
</html>
