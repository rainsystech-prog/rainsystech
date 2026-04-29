<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Rain Systems & Technologies</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;0,600;0,700;1,100..900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="secondary.css">
   <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="auth-page login-page">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-card">
      <div class="card-body p-5">
        <div class="text-center mb-5">
          <h1 class="brand-logo mb-3" style="color:white">Client Login</h1>
          <p class="lead" style="color: white">Welcome back</p>
        </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
            <?php echo $_GET['error']; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="login-process.php">
          <div class="mb-4" style="color: white;">
            <label class="form-label fw-semibold mb-2">Username</label>
            <input type="text" name="username" class="form-control login-input" placeholder="Enter your username" required>
          </div>
          
          <div class="mb-4">
            <label class="form-label fw-semibold mb-2" style="color: white;">Password</label>
            <input type="password" name="password" class="form-control login-input" placeholder="Enter your password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 login-btn mb-4">
            <span>Login</span>
          </button>
        </form>

        <div class="text-center">
          <p class="mb-0" style="color:white">
            Don't have an account? 
            <a href="register.php" class="text-primary fw-semibold" style="text-decoration: none;">Create one</a>
          </p>
        </div>
      </div>
    </div>
  </div>


</body>
</html>