<?php
/**
 * CircleHub - Edit Profile Page
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Please login to edit your profile.');
    redirect('../auth/login.php');
}

// Get current user data
$user = getUserById($pdo, $_SESSION['user_id']);

if (!$user) {
    setFlashMessage('error', 'User not found.');
    redirect('../auth/logout.php');
}

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    
    // Validate full name
    if (empty($fullName)) {
        $errors[] = 'Full name is required.';
    } elseif (strlen($fullName) < 2 || strlen($fullName) > 100) {
        $errors[] = 'Full name must be between 2 and 100 characters.';
    }
    
    // Validate bio length
    if (strlen($bio) > 500) {
        $errors[] = 'Bio must be less than 500 characters.';
    }
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = 'Invalid image type. Only JPG, PNG, and GIF are allowed.';
        } elseif ($file['size'] > $maxSize) {
            $errors[] = 'Image size must be less than 2MB.';
        } else {
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFilename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
            $uploadPath = __DIR__ . '/../uploads/profiles/' . $newFilename;
            
            // Delete old image if exists
            if (!empty($user['profile_image'])) {
                $oldImage = __DIR__ . '/../uploads/profiles/' . $user['profile_image'];
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                updateProfileImage($pdo, $_SESSION['user_id'], $newFilename);
            } else {
                $errors[] = 'Failed to upload image. Please try again.';
            }
        }
    }
    
    // Update profile if no errors
    if (empty($errors)) {
        if (updateUserProfile($pdo, $_SESSION['user_id'], $fullName, $bio)) {
            // Update session name
            $_SESSION['full_name'] = $fullName;
            setFlashMessage('success', 'Profile updated successfully!');
            redirect('profile.php');
        } else {
            $errors[] = 'Failed to update profile. Please try again.';
        }
    }
}

// Refresh user data
$user = getUserById($pdo, $_SESSION['user_id']);

$baseUrl = '..';
$basePath = '..';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/assets/css/style.css" rel="stylesheet">
    <style>
        .profile-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 4px solid #0d6efd;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .profile-preview:hover {
            opacity: 0.8;
        }
        .image-upload-wrapper {
            position: relative;
            display: inline-block;
        }
        .image-upload-overlay {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="mb-1">Edit Profile</h2>
                        <p class="text-muted mb-0">Update your personal information</p>
                    </div>
                    <a href="profile.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Profile
                    </a>
                </div>
                
                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Edit Form -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Profile Image -->
                            <div class="text-center mb-4">
                                <div class="image-upload-wrapper">
                                    <img src="<?php echo getProfileImage($user, $baseUrl); ?>" 
                                         alt="Profile" 
                                         class="rounded-circle profile-preview mb-2"
                                         id="imagePreview"
                                         onclick="document.getElementById('profile_image').click();">
                                    <div class="image-upload-overlay">
                                        <i class="bi bi-camera"></i> Change
                                    </div>
                                </div>
                                <input type="file" 
                                       name="profile_image" 
                                       id="profile_image" 
                                       class="d-none" 
                                       accept="image/jpeg,image/png,image/gif">
                                <p class="text-muted small mt-2 mb-0">Click image to change. Max 2MB (JPG, PNG, GIF)</p>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Username (Read Only) -->
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="<?php echo htmlspecialchars($user['username']); ?>" 
                                           disabled>
                                </div>
                                <small class="text-muted">Username cannot be changed</small>
                            </div>
                            
                            <!-- Email (Read Only) -->
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control bg-light" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" 
                                           disabled>
                                </div>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" 
                                           name="full_name" 
                                           class="form-control" 
                                           value="<?php echo htmlspecialchars($user['full_name']); ?>"
                                           required
                                           minlength="2"
                                           maxlength="100"
                                           placeholder="Enter your full name">
                                </div>
                            </div>
                            
                            <!-- Bio -->
                            <div class="mb-4">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" 
                                          class="form-control" 
                                          rows="4" 
                                          maxlength="500"
                                          placeholder="Tell us a little about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                <small class="text-muted">
                                    <span id="bioCount"><?php echo strlen($user['bio'] ?? ''); ?></span>/500 characters
                                </small>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Save Changes
                                </button>
                                <a href="profile.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    
    <script>
        // Image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Bio character count
        const bioTextarea = document.querySelector('textarea[name="bio"]');
        const bioCount = document.getElementById('bioCount');
        
        bioTextarea.addEventListener('input', function() {
            bioCount.textContent = this.value.length;
        });
    </script>
</body>
</html>
