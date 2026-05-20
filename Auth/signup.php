<?php
/**
 * CircleHub - User Registration
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
    // Get and sanitize input
    $fullName = sanitize($_POST['full_name'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Store old values for form repopulation
    $old = [
        'full_name' => $fullName,
        'username' => $username,
        'email' => $email
    ];
    
    // Validation
    if (empty($fullName)) {
        $errors['full_name'] = 'Full name is required.';
    }
    
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (!isValidUsername($username)) {
        $errors['username'] = 'Username must be 3-20 characters (letters, numbers, underscore only).';
    } elseif (usernameExists($pdo, $username)) {
        $errors['username'] = 'This username is already taken.';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (emailExists($pdo, $email)) {
        $errors['email'] = 'This email is already registered.';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (!isValidPassword($password)) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }
    
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }
    
    // If no errors, create user
    if (empty($errors)) {
        if (createUser($pdo, $username, $email, $password, $fullName)) {
            setFlashMessage('success', 'Account created successfully! Please login.');
            redirect('login.php');
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
    }
}

$pageTitle = 'Sign Up';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-2 mb-1">Create Account</h2>
                        <p class="text-muted">Join CircleHub today</p>
                    </div>
                    
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?php echo $errors['general']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" novalidate>
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['full_name']) ? 'is-invalid' : ''; ?>" 
                                       id="full_name" 
                                       name="full_name" 
                                       placeholder="Enter your full name"
                                       value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['full_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-at"></i></span>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Choose a username"
                                       value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">3-20 characters, letters, numbers, underscore only.</div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Enter your email"
                                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Create a password"
                                       required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Minimum 8 characters.</div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Confirm your password"
                                       required>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Already have an account? <a href="login.php" class="fw-semibold">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
