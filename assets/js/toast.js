
function showToast(message, type = 'success') {
    const $toast = $("#liveToast");
    const $icon = $("#toast-icon");
    const $msg = $("#toast-message");
    $toast.removeClass("toast-success-bg toast-error-bg toast-warning-bg");
    if (type === 'success') {
        $toast.addClass("toast-success-bg");
        $icon.html('<i class="bi bi-check-circle-fill fs-5"></i>');
    } else if (type === 'error') {
        $toast.addClass("toast-error-bg");
        $icon.html('<i class="bi bi-exclamation-triangle-fill fs-5"></i>');
    } else if (type === 'warning') {
        $toast.addClass("toast-warning-bg");
        $icon.html('<i class="bi bi-exclamation-circle-fill fs-5"></i>');
    }
    
    $msg.text(message);
    

    const bsToast = new bootstrap.Toast($toast[0], { delay: 4000 }); 
    bsToast.show();
}