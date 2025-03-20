/******************************* AFFICHER LE MDP **********************************/
function togglePassword(inputId, icon) {
    const passwordInput = document.getElementById(inputId);
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.src = "img/eye.png"; 
    } else {
        passwordInput.type = "password";
        icon.src = "img/open-eye.png"; 
    }
}
