<?php
/**
 * CircleHub - User Login
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
requireGuest();

$errors = [];
$old = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = sanitize($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $old['login'] = $login;
    
    // Validation
    if (empty($login)) {
        $errors['login'] = 'Username or email is required.';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }
    
    // If no validation errors, attempt login
    if (empty($errors)) {
        $user = getUserByLogin($pdo, $login);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            initUserSession($user);
            updateLastLogin($pdo, $user['id']);
            
            setFlashMessage('success', 'Welcome back, ' . $user['full_name'] . '!');
            redirect('../index.php');
        } else {
            $errors['general'] = 'Invalid username/email or password.';
        }
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Get flash message
$flash = getFlashMessage();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-2 mb-1">Welcome Back</h2>
                        <p class="text-muted">Login to your account</p>
                    </div>
                    
                    <?php if ($flash): ?>
                        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i><?php echo $flash['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?php echo $errors['general']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" novalidate>
                        <!-- Username/Email -->
                        <div class="mb-3">
                            <label for="login" class="form-label">Username or Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['login']) ? 'is-invalid' : ''; ?>" 
                                       id="login" 
                                       name="login" 
                                       placeholder="Enter username or email"
                                       value="<?php echo htmlspecialchars($old['login'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['login'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['login']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Don't have an account? <a href="signup.php" class="fw-semibold">Sign up here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
