<?php
include_once './liens_utiles/header.php';
include_once '../connexion/dbcon.php';
?>

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="grid">
            <div class="grid-item" id="eleves">
                <i class="fas fa-user-graduate"></i>
                <p>Gestion des Élèves</p>
                <p class="total">Total: <span id="total-eleves">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_eleves FROM eleves";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total d'élèves
                            $row = $result->fetch_assoc();
                            $totalEleves = $row["total_eleves"];

                            // Afficher le nombre total d'élèves
                            echo "" . $totalEleves;
                        } else {
                            echo "0";
                        }

                        // Fermer la connexion à la base de données

                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="staffs">
                <i class="fas fa-chalkboard-teacher"></i>
                <p>Gestion des Staffs</p>
                <p class="total">Total: <span id="total-staffs">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_staffs FROM staffs";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total de staffs
                            $row = $result->fetch_assoc();
                            $totalStaffs = $row["total_staffs"];

                            // Afficher le nombre total de staffs
                            echo "" . $totalStaffs;
                        } else {
                            echo "0";
                        }

                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="matieres">
                <i class="fas fa-book"></i>
                <p>Gestion des Matières</p>
                <p class="total">Total: <span id="total-matieres">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_matieres FROM matieres";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total de matières
                            $row = $result->fetch_assoc();
                            $totalMatieres = $row["total_matieres"];

                            // Afficher le nombre total de matières
                            echo "" . $totalMatieres;
                        } else {
                            echo "0";
                        }
                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="salles-classe">
                <i class="fas fa-school"></i>
                <p>Gestion des Salles de Classe</p>
                <p class="total">Total: <span id="total-salles-classe">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_salles_classe FROM salles_classe";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total de salles de classe
                            $row = $result->fetch_assoc();
                            $totalSallesClasse = $row["total_salles_classe"];

                            // Afficher le nombre total de salles de classe
                            echo "" . $totalSallesClasse;
                        } else {
                            echo "0";
                        }
                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="notes">
                <i class="fas fa-clipboard"></i>
                <p>Gestion des Notes</p>
                <p class="total">Total: <span id="total-notes">

                        <?php
                        $sql = "SELECT COUNT(*) AS total_notes FROM notes";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total de notes
                            $row = $result->fetch_assoc();
                            $totalNotes = $row["total_notes"];

                            // Afficher le nombre total de notes
                            echo "" . $totalNotes;
                        } else {
                            echo "0";
                        }

                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="emplois-temps">
                <i class="fas fa-calendar-alt"></i>
                <p>Emplois du Temps</p>
                <p class="total">Total: <span id="total-emplois-temps">

                        <?php
                        $sql = "SELECT COUNT(*) AS total_emplois_temps FROM emplois_temps";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total d'emplois du temps
                            $row = $result->fetch_assoc();
                            $totalEmploisTemps = $row["total_emplois_temps"];

                            // Afficher le nombre total d'emplois du temps
                            echo "" . $totalEmploisTemps;
                        } else {
                            echo "0";
                        }
                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="notifications">
                <i class="fas fa-bell"></i>
                <p>Notifications</p>
                <p class="total">Total: <span id="total-notifications">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_notifications FROM notifications";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total de notifications
                            $row = $result->fetch_assoc();
                            $totalNotifications = $row["total_notifications"];
                        
                            // Afficher le nombre total de notifications
                            echo "" . $totalNotifications;
                        } else {
                            echo "Aucun résultat trouvé.";
                        }
                        
                        ?>
                    </span></p>
            </div>
            <div class="grid-item" id="evaluations">
                <i class="fas fa-file-alt"></i>
                <p>Gestion des Évaluations</p>
                <p class="total">Total: <span id="total-evaluations">

                <?php
                        $sql = "SELECT COUNT(*) AS total_evaluations FROM evaluations";

                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total d'évaluations
                            $row = $result->fetch_assoc();
                            $totalEvaluations = $row["total_evaluations"];
                        
                            // Afficher le nombre total d'évaluations
                            echo "" . $totalEvaluations;
                        } else {
                            echo "0";
                        }
                        
                ?>
                </span></p>
            </div>
            <div class="grid-item" id="absences">
                <i class="fas fa-user-clock"></i>
                <p>Suivi des Absences</p>
                <p class="total">Total: <span id="total-absences">
                    <?php
                        $sql = "SELECT COUNT(*) AS total_absences FROM absences";

                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            // Récupérer le nombre total d'absences
                            $row = $result->fetch_assoc();
                            $totalAbsences = $row["total_absences"];
                        
                            // Afficher le nombre total d'absences
                            echo "" . $totalAbsences;
                        } else {
                            echo "Aucun résultat trouvé.";
                        }
                    ?>
                </span></p>
            </div>
        </div>
       <h1>Graphique d'évolutions</h1> 
         <div class="container">
        <canvas id="myChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    </div>
</div>

<style>
    .container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .grid-item {
        background-color: #ffffff;
        border: 2px solid #ddd;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .grid-item:hover {
        transform: scale(1.05);
    }

    .grid-item i {
        font-size: 40px;
        margin-bottom: 10px;
        color: #555;
    }

    .grid-item p {
        margin: 0;
        font-size: 18px;
        color: #333;
    }
</style>
<script>
    // Fonction pour récupérer les données depuis PHP
function getData() {
    return new Promise((resolve, reject) => {
        // Effectuer une requête AJAX pour récupérer les données depuis PHP
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                resolve(JSON.parse(xhr.responseText));
            }
        };
        xhr.open("GET", "./data.php", true);
        xhr.send();
    });
}

// Créer et afficher le graphique
async function createChart() {
    // Récupérer les données depuis PHP
    var data = await getData();

    // Extraire les étiquettes et les données de chaque série
    var labels = data.labels;
    var datasets = data.datasets;

    // Créer un tableau de couleurs pour les séries
    var colors = ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'];

    // Créer un objet de configuration du graphique
    var config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets.map((dataset, index) => {
                return {
                    label: dataset.label,
                    data: dataset.data,
                    borderColor: colors[index],
                    backgroundColor: colors[index].replace(')', ', 0.2)'),
                    borderWidth: 2
                }
            })
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Temps'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Valeur'
                    }
                }
            }
        }
    };

    // Créer le graphique
    var ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, config);
}

// Appeler la fonction pour créer le graphique
createChart();

</script>
<?php
include_once './liens_utiles/footer.php';
?>