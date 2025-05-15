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

/*********************************Filtrage page Mes demandes *************************************/
const filters = {
        type: document.querySelector("#search-type"),
        demande: document.querySelector("#search-demande"),
        debut: document.querySelector("#search-debut"),
        fin: document.querySelector("#search-fin"),
        jours: document.querySelector("#search-jours"),
        statut: document.querySelector("#search-statut")
    };

    Object.values(filters).forEach(input => {
        input.addEventListener("keyup", filterRows);
    });

    function filterRows() {
        const rows = document.querySelectorAll(".card");

        rows.forEach(row => {
            const type = row.querySelector(".Type1").textContent.toLowerCase();
            const demande = row.querySelector(".DemandeDate").textContent.toLowerCase();
            const debut = row.querySelector(".DebutDate").textContent.toLowerCase();
            const fin = row.querySelector(".FinDate").textContent.toLowerCase();
            const jours = row.querySelector(".NbJours").textContent.toLowerCase();
            const statut = row.querySelector(".Statut").textContent.toLowerCase();

            const matches = (
                type.includes(filters.type.value.toLowerCase()) &&
                demande.includes(filters.demande.value.toLowerCase()) &&
                debut.includes(filters.debut.value.toLowerCase()) &&
                fin.includes(filters.fin.value.toLowerCase()) &&
                jours.includes(filters.jours.value.toLowerCase()) &&
                statut.includes(filters.statut.value.toLowerCase())
            );

            row.style.display = matches ? "table-row" : "none";
        });
    }
    document.querySelectorAll('.sortable').forEach(header => {
        let asc = true;
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr.card'));
            const columnIndex = parseInt(header.dataset.column);
            const type = header.dataset.type;
            const arrow = header.querySelector('.arrow');

            // Restaurer les flèches
            document.querySelectorAll('.sortable .arrow').forEach(el => el.textContent = '▲');

            rows.sort((a, b) => {
                const aText = a.children[columnIndex].textContent.trim();
                const bText = b.children[columnIndex].textContent.trim();

                if (type === "number") {
                    return asc ? aText - bText : bText - aText;
                } else if (type === "date") {
                    const parseDate = str => {
                        const parts = str.split(/[\/\s:h]/);
                        return new Date(`${parts[2]}-${parts[1]}-${parts[0]}T${parts[3] || "00"}:${parts[4] || "00"}`);
                    };
                    return asc ? parseDate(aText) - parseDate(bText) : parseDate(bText) - parseDate(aText);
                } else {
                    return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                }
            });

            // Inverse l’ordre
            asc = !asc;
            arrow.textContent = asc ? '▲' : '▼';

            // Réinsère les lignes triées
            rows.forEach(row => tbody.appendChild(row));
        });
    });