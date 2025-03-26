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

/******************************* APPLIQUE LA CLASS ACTIVE **********************************/
document.addEventListener("DOMContentLoaded", function () {
    // Récupérer l'URL actuelle sans le domaine
    let currentPage = window.location.pathname.split("/").pop();

    // Sélectionner tous les liens du menu
    let links = document.querySelectorAll(".left a");

    // Parcourir chaque lien
    links.forEach(link => {
        // Comparer le href du lien avec la page actuelle
        if (link.getAttribute("href") === currentPage) {
            // Supprimer la classe active de tous les liens
            links.forEach(l => l.classList.remove("active"));
            
            // Ajouter la classe active au lien correspondant
            link.classList.add("active");
        }
    });
});

/******************************* MENU DEROULANT **********************************/
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropbtn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const dropdownContent = this.nextElementSibling; // Sélectionne le menu déroulant

            if (dropdownContent.style.display === "flex") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "flex";
            }
        });
    });
});


/******************************* BURGER MENU **********************************/

document.getElementById("openMenu").addEventListener("click", function () {
    document.getElementById("menu").style.left = "0"; 
    document.getElementById("menuOverlay").style.display = "block";
  });
  
  document.getElementById("closeMenu").addEventListener("click", function () {
    document.getElementById("menu").style.left = "-500px"; 
    document.getElementById("menuOverlay").style.display = "none";
  });
  
  document.getElementById("menuOverlay").addEventListener("click", function () {
    document.getElementById("menu").style.left = "-500px"; 
    this.style.display = "none";
});


  
  