<?php
include 'includes/config.php';

if (isLoggedIn()) {
    redirect($_SESSION['user_role'] . '/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $role = 'client';

    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
    } elseif ($password != $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $errors[] = "Le nom d'utilisateur ou l'email est déjà utilisé";
    }
    
    if (empty($errors)) {
   
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
    
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, full_name, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $email, $role, $full_name, $phone]);
        
 
        $user_id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_role'] = $role;
        $_SESSION['username'] = $username;
        
        redirect('client/dashboard.php');
    }
}

$page_title = "Inscription";
include 'includes/header.php';
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
  
    .auth-container {
        max-width: 600px;
        margin: 80px auto;
        padding: 40px;
        background: rgba(30, 30, 30, 0.8);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(230, 57, 70, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .auth-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(230, 57, 70, 0.05) 0%, transparent 70%);
        z-index: -1;
    }
    
    .auth-container h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2.2rem;
        color: white;
        position: relative;
    }
    
    .auth-container h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--red);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #ddd;
        font-weight: 600;
    }
    
    .form-group input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(20, 20, 20, 0.7);
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.2);
    }
    
    .form-row {
        display: flex;
        gap: 20px;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    .btn-block {
        width: 100%;
        padding: 16px;
        background: var(--red);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 10px;
    }
    
    .btn-block:hover {
        background: var(--light-red);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
    }
    
    .text-center {
        text-align: center;
        color:white;
        margin-top: 20px;
    }
    
    .auth-container a {
        color: var(--light-red);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .auth-container a:hover {
        color: white;
        text-decoration: underline;
    }
    
    .alert-danger {
        background: rgba(230, 57, 70, 0.2);
        border-left: 4px solid var(--red);
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 4px;
        color: white;
    }
    
    .alert-danger p {
        margin: 5px 0;
    }
    
    .password-strength {
        margin-top: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
    }
    
    .password-strength-bar {
        height: 100%;
        width: 0%;
        background: var(--red);
        transition: width 0.3s ease;
    }
    
   
    @media (max-width: 768px) {
        .auth-container {
            margin: 40px 20px;
            padding: 30px;
        }
        
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
    
    @media (max-width: 480px) {
        .auth-container {
            padding: 25px 20px;
        }
        
        .auth-container h2 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="auth-container">
    <h2>Créer un compte</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="register.php" method="post">
        <div class="form-row">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <div class="password-strength">
                    <div class="password-strength-bar" id="password-strength-bar"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Nom complet</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-block">S'inscrire</button>
    </form>
    
    <p class="text-center">Déjà membre? <a href="login.php">Connectez-vous ici</a></p>
</div>

<script>
 
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        strengthBar.style.width = strength + '%';
        
        if (strength < 30) {
            strengthBar.style.background = '#e63946';
        } else if (strength < 70) {
            strengthBar.style.background = '#f4a261';
        } else {
            strengthBar.style.background = '#2a9d8f'; 
        }
    });
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
   
        strength += Math.min(50, (password.length / 12) * 50);
        
      
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 20;
        
     
        if (/\d/.test(password)) strength += 15;
        
    
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 15;
        
        return Math.min(100, strength);
    }
</script>

<?php include 'includes/footer.php'; ?>