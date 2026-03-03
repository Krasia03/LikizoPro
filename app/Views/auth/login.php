<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #FBFBFF;
            --accent-blue: #3581B8;
            --primary-navy: #111D4A;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 440px;
            width: 100%;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(17, 29, 74, 0.08);
            padding: 40px;
        }
        
        .brand-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .brand-logo h1 {
            color: var(--primary-navy);
            font-weight: 800;
            font-size: 28px;
            margin: 0;
        }
        
        .brand-logo span {
            color: var(--accent-blue);
        }
        
        .brand-logo p {
            color: #6B7280;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            background: #F9FAFB;
        }
        
        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(53, 129, 184, 0.1);
            background: white;
        }
        
        .btn-login {
            background: var(--accent-blue);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            background: #2d6a9e;
            transform: translateY(-1px);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
        
        .input-icon .form-control {
            padding-left: 42px;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 24px;
            color: #9CA3AF;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-logo">
                <h1>LIKIZO<span>PRO</span></h1>
                <p>Enterprise Leave Management System</p>
            </div>
            
            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= session()->get('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->get('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            
            <?= form_open('/auth/doLogin', ['method' => 'post']) ?>
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="<?= old('email') ?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>
            <?= form_close() ?>
            
            <div class="footer-text">
                <p>&copy; <?= date('Y') ?> LIKIZOPRO. All rights reserved.</p>
                <p>Default Login: <strong>admin@likizopro.co.tz</strong> / <strong>123456</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
