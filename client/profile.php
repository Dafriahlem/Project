<?php
include '../includes/config.php';
checkRole('client');

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    $errors = [];
    
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }
    
   
    if (!empty($new_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = "Le mot de passe actuel est incorrect";
        }
        
        if ($new_password != $confirm_password) {
            $errors[] = "Les nouveaux mots de passe ne correspondent pas";
        }
        
        if (strlen($new_password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }
    }
    
    if (empty($errors)) {
  
        $update_data = [
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'id' => $user_id
        ];
        
        $sql = "UPDATE users SET full_name = :full_name, email = :email, phone = :phone";
        
     
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_data['password'] = $hashed_password;
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($update_data);
        
        $_SESSION['success_message'] = "Profil mis à jour avec succès";
        redirect('profile.php');
    }
}

$page_title = "Mon Profil";
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

    h3 {
        color: var(--light-red);
        margin-top: 30px;
        margin-bottom: 20px;
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
        padding: 30px;
        border-radius: 12px;
        border: 1px solid rgba(230, 57, 70, 0.1);
        transition: var(--transition);
    }

    .card:hover {
        border-color: rgba(230, 57, 70, 0.3);
        box-shadow: 0 6px 15px rgba(230, 57, 70, 0.1);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 30px;
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

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--light);
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: var(--light);
        transition: var(--transition);
    }

    .form-group input:focus {
        border-color: var(--light-red);
        outline: none;
    }

    .form-group input:disabled {
        background: rgba(255, 255, 255, 0.02);
        color: rgba(255, 255, 255, 0.5);
    }

    .btn {
        background: var(--red);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
        font-size: 1rem;
    }

    .btn:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }

    .password-note {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="subscription.php"><i class="fas fa-credit-card"></i> Mon Abonnement</a></li>
            <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Mon Profil</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>Mon Profil</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="card">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="full_name">Nom complet</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            
            <h3>Changer le mot de passe</h3>
            <p class="password-note">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe</p>
            
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <button type="submit" name="update_profile" class="btn">Mettre à jour</button>
        </form>
    </div>
</div>

