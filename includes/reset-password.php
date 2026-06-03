<?php
$config = require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? 'Recycle Pro';

if (!isset($_GET['email']) || !isset($_GET['token']) || empty($_GET['email']) || empty($_GET['token'])) {
    header("Location: /shop/");
    exit;
}

$raw_encoded_email = $_GET['email'];
$clean_token = trim($_GET['token']);
$decoded_email = base64_decode(urldecode($raw_encoded_email), true);
if ($decoded_email === false || !filter_var($decoded_email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /shop/");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password - Recycle Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            max-width: 440px;
            width: 100%;
            padding: 32px;
        }
        .brand-logo {
            font-size: 22px;
            font-weight: 700;
            color: #000000;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 24px;
        }
        .btn-theme {
            background-color: #13564f;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s ease;
        }
        .btn-theme:hover {
            background-color: #0d3d38;
            color: #ffffff;
        }
        .form-control {
            padding: 11px 16px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #2c7c74;
        }
        .input-group-text {
            background: #ffffff;
            border: 1px solid #dee2e6;
            cursor: pointer;
            color: #6c757d;
            border-radius: 0 8px 8px 0;
        }
        /* Initial screen lock during token validation */
        #pageLoader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #f5f8fa; z-index: 9999;
            display: flex; align-items: center; justify-content: center;
        }
    </style>


<script>
const baseAPI = "<?= $config['API_URL'] ?>";
const BASE_URL = "<?= $config['BASE_URL'] ?>";
</script>
</head>
<body>

<div id="pageLoader">
    <div class="text-center">
        <div class="spinner-border text-success mb-2" role="status"></div>
        <p class="text-muted small fw-medium">Verifying your secure link...</p>
    </div>
</div>

<div class="container d-flex justify-content-center">
    <div class="reset-card text-center">
        
        <a href="/shop/" class="brand-logo">Recycle Pro</a>
        
        <h4 class="fw-bold text-start mb-2" style="color: #212529; letter-spacing: -0.5px;">Create New Password</h4>
        <p class="text-muted text-start small mb-4">Your new password must be different from previous used passwords.</p>
        
        <form id="resetPasswordForm">
            
            <div class="mb-3 text-start">
                <label for="newPassword" class="form-label fw-semibold small text-secondary">New Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="newPassword" placeholder="Minimum 8 characters" required style="border-right: none;">
                    <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('newPassword', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="mb-4 text-start">
                <label for="confirmPassword" class="form-label fw-semibold small text-secondary">Confirm New Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Repeat new password" required style="border-right: none;">
                    <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('confirmPassword', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            
            <div id="statusMessage" class="alert d-none small py-2 text-start" role="alert"></div>
            
            <button type="submit" id="submitBtn" class="btn btn-theme w-100 mb-3">
                <span class="btn-text">Reset Password</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
            
            <div class="text-center">
                <a href="/shop/" class="small text-decoration-none fw-semibold" style="color: #13564f;">
                    <i class="bi bi-arrow-left me-1"></i> Back to Homepage
                </a>
            </div>
            
        </form>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    var globalEmail = "<?php echo htmlspecialchars($decoded_email, ENT_QUOTES, 'UTF-8'); ?>";
    var globalToken = "<?php echo htmlspecialchars($clean_token, ENT_QUOTES, 'UTF-8'); ?>";


    function togglePasswordVisibility(inputId, element) {
        const input = document.getElementById(inputId);
        const icon = element.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    $(document).ready(function() {
        

        $.ajax({
            url: `${baseAPI}wp-json/wp/v2/verify-token`, 
            method: 'POST',
            data: {
                token: globalToken,
                email: globalEmail
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    $('#pageLoader').fadeOut(300);
                } else {

                    window.location.href = '/shop/';
                }
            },
            error: function() {
                window.location.href = '/shop/';
            }
        });


        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            const pass = $('#newPassword').val();
            const confirmPass = $('#confirmPassword').val();
            const $statusBox = $('#statusMessage');
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');
            const $spinner = $submitBtn.find('.spinner-border');

            $statusBox.addClass('d-none').removeClass('alert-danger alert-success');

            if (pass.length < 8) {
                $statusBox.removeClass('d-none').addClass('alert-danger').text("Password must be at least 8 characters long.");
                return;
            } 
            
            if (pass !== confirmPass) {
                $statusBox.removeClass('d-none').addClass('alert-danger').text("Passwords do not match!");
                return;
            }


            $submitBtn.prop('disabled', true);
            $btnText.text('Updating Password...');
            $spinner.removeClass('d-none');


            $.ajax({
                url: `${baseAPI}wp-json/wp/v2/reset-password`, 
                method: 'POST',
                data: {
                    token: globalToken,
                    email: globalEmail,
                    password: pass
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        $statusBox.removeClass('d-none alert-danger').addClass('alert-success')
                                  .text("Password changed successfully! Redirecting to home...");
                        
                        setTimeout(function() {
                            window.location.href = '/shop/';
                        }, 2000);
                    } else {
                        resetButtonState();
                        $statusBox.removeClass('d-none').addClass('alert-danger').text(response.message || "Failed to update password.");
                    }
                },
                error: function() {
                    resetButtonState();
                    $statusBox.removeClass('d-none').addClass('alert-danger').text("Server error. Please try again later.");
                }
            });

            function resetButtonState() {
                $submitBtn.prop('disabled', false);
                $btnText.text('Reset Password');
                $spinner.addClass('d-none');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>