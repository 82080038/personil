<?php
/**
 * Login Page SMO-BAGOPS
 */

require_once 'core/auth.php';

// Redirect jika sudah login
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nrp = $_POST['nrp'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($nrp) || empty($password)) {
        $error = 'NRP dan password wajib diisi';
    } else {
        $result = login($nrp, $password);
        
        if ($result['success']) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --polri-blue: #003366;
            --polri-gold: #C9A227;
        }
        
        body {
            background: linear-gradient(135deg, var(--polri-blue) 0%, #001a33 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 90%;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--polri-blue) 0%, #004080 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .login-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .login-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-control:focus {
            border-color: var(--polri-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--polri-blue) 0%, #004080 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #004080 0%, var(--polri-blue) 100%);
        }
        
        .security-notice {
            background: #fff3cd;
            border-left: 4px solid var(--polri-gold);
            padding: 15px;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="bi bi-shield-shaded"></i>
            <h4><?php echo APP_NAME; ?></h4>
            <p><?php echo APP_FULLNAME; ?></p>
        </div>
        
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nrp" class="form-label">
                        <i class="bi bi-person-badge me-1"></i>NRP / NIP
                    </label>
                    <input type="text" class="form-control" id="nrp" name="nrp" 
                           placeholder="Masukkan NRP atau NIP" required autofocus>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-1"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN
                </button>
            </form>
            
            <div class="security-notice">
                <i class="bi bi-shield-check me-2"></i>
                <strong>Perhatian:</strong> Akses sistem ini dilindungi oleh UU No. 27 Tahun 2022 
                tentang Perlindungan Data Pribadi. Semua aktivitas tercatat dalam audit log.
            </div>
            
            <div class="footer-text">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo POLRES_NAMA; ?></p>
                <p class="mb-0"><?php echo SATWIL; ?></p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
