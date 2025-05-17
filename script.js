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
document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector(".table1");
    const headers = table.querySelectorAll(".sortable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr.card"));

    let currentSort = {
        column: null,
        direction: 1
    };

    function parseValue(value, type) {
        if (type === "number") return parseFloat(value) || 0;
        if (type === "date") {
            const [day, month, yearHour] = value.split('/');
            if (!yearHour) return new Date("Invalid");
            const [year, time] = yearHour.split(' ');
            return new Date(`${year}-${month}-${day} ${time}`);
        }
        return value.toLowerCase();
    }

    function sortTable(columnIndex, type) {
        const direction = (currentSort.column === columnIndex) ? -currentSort.direction : 1;
        currentSort = { column: columnIndex, direction };

        rows.sort((a, b) => {
            const aText = a.children[columnIndex].textContent.trim();
            const bText = b.children[columnIndex].textContent.trim();
            const aVal = parseValue(aText, type);
            const bVal = parseValue(bText, type);
            if (aVal < bVal) return -1 * direction;
            if (aVal > bVal) return 1 * direction;
            return 0;
        });

        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));
        updateArrows(columnIndex, direction);
    }

    function updateArrows(index, direction) {
        headers.forEach((header, i) => {
            const arrow = header.querySelector(".arrow");
            if (arrow) {
                if (i === index) {
                    arrow.textContent = direction === 1 ? "▲" : "▼";
                } else {
                    arrow.textContent = "▲";
                }
            }
        });
    }

    headers.forEach((header, i) => {
        header.addEventListener("click", (e) => {
            // Ne rien faire si le clic vient de l'input ou de ses enfants
            if (e.target.closest("input")) return;

            const type = header.getAttribute("data-type");
            sortTable(i, type);
        });
    });

    // FILTRAGE
    const filters = table.querySelectorAll(".searchListe");

    filters.forEach((input, index) => {
        input.addEventListener("input", () => {
            const searchTerms = Array.from(filters).map(input => input.value.toLowerCase().trim());

            tbody.querySelectorAll("tr.card").forEach(row => {
                const cells = row.querySelectorAll("td");
                const matches = searchTerms.every((term, i) => {
                    return cells[i].textContent.toLowerCase().includes(term);
                });
                row.style.display = matches ? "" : "none";
            });
        });
    });
});

/********************************* Animation / Effect Fade In *******************************/
function loadPage(url) {
  const content = document.getElementById('content');
  fetch(url)
    .then(response => response.text())
    .then(html => {
      content.classList.remove('fade-in'); // reset
      content.innerHTML = html;
      void content.offsetWidth; // forcer le reflow
      content.classList.add('fade-in');
    });
}


/********************************* Manager details *******************************/
function openModal() {
            document.getElementById('confirmModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

/********************************* MDP *******************************/
        function closePopup() {
      document.querySelector('.popup').style.display = 'none';
      document.querySelector('.overlay').style.display = 'none';
    }
/********************************* Nouvelle demande *******************************/
document.addEventListener("DOMContentLoaded", function() {
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');
            const daysInput = document.querySelector('input[name="date"]');

            // Liste des jours fériés en France (ajoute/modifie selon besoin)
            const holidays = [
                "2025-01-01", // Nouvel An
                "2025-04-21", // Lundi de Pâques
                "2025-05-01", // Fête du Travail
                "2025-05-08", // Victoire 1945
                "2025-05-29", // Ascension
                "2025-06-09", // Lundi de Pentecôte
                "2025-07-14", // Fête Nationale
                "2025-08-15", // Assomption
                "2025-11-01", // Toussaint
                "2025-11-11", // Armistice 1918
                "2025-12-25" // Noël
            ];

            function calculateDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (isNaN(startDate) || isNaN(endDate) || startDate > endDate) {
                    daysInput.value = 0;
                    return;
                }

                let count = 0;
                let currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    const dayOfWeek = currentDate.getDay(); // 0 = Dimanche, 6 = Samedi
                    const formattedDate = currentDate.toISOString().split('T')[0]; // Format YYYY-MM-DD

                    // Vérifier si le jour n'est pas un week-end et pas un jour férié
                    if (dayOfWeek !== 0 && dayOfWeek !== 6 && !holidays.includes(formattedDate)) {
                        count++;
                    }

                    // Passer au jour suivant
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                daysInput.value = count;
            }

            // Écouter les changements sur les inputs de dates
            startDateInput.addEventListener("change", calculateDays);
            endDateInput.addEventListener("change", calculateDays);
        });
/********************************* Poste *******************************/
document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('search-title');
    const countInput = document.getElementById('search-count');
    const rows       = [...document.querySelectorAll('tbody tr')];
    const headers    = document.querySelectorAll('th[data-sort]');

    const filterRows = () => {
        const tVal = titleInput.value.trim().toLowerCase();
        const cVal = countInput.value.trim();
        rows.forEach(r => {
            const tText = r.querySelector('[data-col="title"]').textContent.toLowerCase();
            const cText = r.querySelector('[data-col="count"]').textContent.trim();
            const okT = tText.includes(tVal);
            const okC = cVal === '' || cText === cVal;
            r.style.display = (okT && okC) ? '' : 'none';
        });
    };
    titleInput.addEventListener('input', filterRows);
    countInput.addEventListener('input', filterRows);

    const sortTable = (key, order) => {
        const factor = order === 'asc' ? 1 : -1;
        rows.sort((a, b) => {
            const aVal = a.querySelector(`[data-col="${key}"]`).textContent.trim();
            const bVal = b.querySelector(`[data-col="${key}"]`).textContent.trim();
            if (key === 'count') {
                return (Number(aVal) - Number(bVal)) * factor;
            }
            return aVal.localeCompare(bVal) * factor;
        });
        const tbody = document.querySelector('tbody');
        rows.forEach(r => tbody.appendChild(r));
    };

    headers.forEach(h => {
        h.addEventListener('click', () => {
            const key = h.dataset.sort;
            const currentOrder = h.dataset.order;
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

            headers.forEach(el => {
                el.textContent = el.textContent.replace('▲','').replace('▼','').trim() + ' ▲';
                el.dataset.order = 'asc';
            });

            h.dataset.order = newOrder;
            h.textContent = h.textContent.replace('▲','').replace('▼','').trim() + (newOrder === 'asc' ? ' ▲' : ' ▼');

            sortTable(key, newOrder);
            filterRows();
        });
    });
});
