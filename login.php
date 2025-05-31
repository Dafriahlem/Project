<?php
include 'includes/config.php';

if (isLoggedIn()) {
    redirect($_SESSION['user_role'] . '/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            
         
            if ($user['role'] == 'client') {
                $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND end_date >= CURDATE() AND payment_status = 'paid'");
                $stmt->execute([$user['id']]);
                $subscription = $stmt->fetch();
                
                if (!$subscription) {
                    $_SESSION['subscription_expired'] = true;
                }
            }
            
            redirect($user['role'] . '/dashboard.php');
        } else {
            $errors[] = "Nom d'utilisateur ou mot de passe incorrect";
        }
    }
}

$page_title = "Connexion";
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
        max-width: 500px;
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
        margin-bottom: 25px;
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
    
    
    @media (max-width: 768px) {
        .auth-container {
            margin: 40px 20px;
            padding: 30px;
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
    <h2>Connexion</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="username">Nom d'utilisateur ou Email</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-block">Se connecter</button>
        </div>
        
     
    </form>
    
    <p class="text-center">Pas encore membre? <a href="register.php">Créez un compte</a></p>
</div>

<?php include 'includes/footer.php'; ?>