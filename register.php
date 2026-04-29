<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Rain Systems & Technologies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;0,600;0,700;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="secondary.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="auth-page register-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="register-card">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h1 class="brand-logo mb-3">Client <br>Registration</h1>
                    <p class="lead text-muted">Create your account</p>
                </div>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register-process.php" id="registerForm">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">First Name *</label>
                            <input type="text" name="first_name" class="form-control register-input" placeholder="John" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">Last Name *</label>
                            <input type="text" name="last_name" class="form-control register-input" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Date of Birth *</label>
                        <input type="date" name="birthday" class="form-control register-input" max="2006-12-04" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Email *</label>
                        <input type="email" name="email" class="form-control register-input" placeholder="john@example.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Phone *</label>
                        <input type="tel" name="phone" class="form-control register-input" placeholder="+63 123 456 789" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Username *</label>
                        <input type="text" name="username" class="form-control register-input" placeholder="john_doe" required>
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="form-label fw-semibold mb-2">Password *</label>
                        <input type="password" name="password" id="regPassword" class="form-control register-input pe-5" placeholder="At least 8 characters" required>
                        <button type="button" class="btn-toggle-pass" data-target="#regPassword" title="Toggle password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="form-label fw-semibold mb-2">Confirm Password *</label>
                        <input type="password" name="confirm_password" id="regConfirm" class="form-control register-input pe-5" placeholder="Repeat password" required>
                        <button type="button" class="btn-toggle-pass" data-target="#regConfirm" title="Toggle password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Service Address *</label>
                        <textarea name="address" class="form-control register-input" rows="3" placeholder="123 Main St, Brgy. Sample, City, Province" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 register-btn mb-4">
                        Create Account
                    </button>
                </form>

                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="login.php" class="text-primary fw-semibold" style="text-decoration: none;">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-toggle-pass').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.querySelector(this.dataset.target);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>

    <script>
        <?php if (isset($_GET['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_GET['error']; ?>',
                footer: '<a href="#">Need help?</a>'
            });
        <?php elseif (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_GET['success']; ?>',
                confirmButtonText: 'Go to Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>