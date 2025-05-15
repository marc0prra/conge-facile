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
    let currentPage = window.location.pathname.split("/").pop();

    // Sélectionner tous les liens du menu
    let links = document.querySelectorAll(".left a");

    links.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            links.forEach(l => l.classList.remove("active"));
            
            link.classList.add("active");
        }
    });
});

/******************************* MENU DEROULANT **********************************/
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropbtn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            // On récupère le conteneur parent de type .dropdown
            const parentDropdown = this.closest(".dropdown");

            // On bascule la classe 'active' pour afficher/masquer .dropdown-contentBurger
            parentDropdown.classList.toggle("active");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropbtnBurger").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            // On récupère le conteneur parent de type .dropdownBurger
            const parentDropdown = this.closest(".dropdownBurger");

            // On bascule la classe 'active' pour afficher/masquer .dropdown-contentBurger
            parentDropdown.classList.toggle("active");
        });
    });
});

/******************************* Pop up mdp **********************************/

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("customModal");
    const resetButton = document.querySelector(".reset-button");
    const confirmBtn = document.getElementById("confirmBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const form = document.getElementById("resetForm");
  
    resetButton.addEventListener("click", function (e) {
      e.preventDefault();
      modal.style.display = "flex";
    });
  
    confirmBtn.addEventListener("click", function () {
      modal.style.display = "none";
      form.submit();
    });
  
    cancelBtn.addEventListener("click", function () {
      modal.style.display = "none";
    });
  });

const modal = document.getElementById("customModal");
const confirmBtn = document.getElementById("confirmBtn");
const cancelBtn = document.getElementById("cancelBtn");

function openModal() {
    modal.style.display = "flex";
}

cancelBtn.onclick = function () {
    modal.style.display = "none";
}

confirmBtn.onclick = function () {
    document.getElementById("resetForm").submit();
}

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
  

/******************************* BURGER MENU **********************************/

document.getElementById("openMenu").addEventListener("click", function () {
    document.getElementById("menu").style.left = "0";
    document.getElementById("menuOverlay").style.display = "block";
  });
  
  document.getElementById("closeMenu").addEventListener("click", function () {
    document.getElementById("menu").style.left = "-300px";
    document.getElementById("menuOverlay").style.display = "none";
  });
  
  document.getElementById("menuOverlay").addEventListener("click", function () {
    document.getElementById("menu").style.left = "-300px";
    this.style.display = "none";
  });
  



/**************************************** POP UP ***************************************/  
document.getElementById('triggerDelete').addEventListener('click', function () {
    document.getElementById('deleteModal').classList.remove('hidden');
});

document.getElementById('cancelDelete').addEventListener('click', function () {
    document.getElementById('deleteModal').classList.add('hidden');
});

document.getElementById('confirmDelete').addEventListener('click', function () {
    document.getElementById('submitDelete').click();
});


function openModal() {
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}