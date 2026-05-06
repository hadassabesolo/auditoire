
<?php
session_start(); // ce pour stocker tous ce que l'utilisateur va entrer tous au long de sa presence dans l'application
include("auditoire.php");
// Étape 0 : Récupération des données du formulaire (POST)
$auditoire_saisi  = $_POST['aud'];
$matricule_saisi  = $_POST['matricule'];
$promo_saisie     = $_POST['promotion'];
$jour_saisi       = $_POST['jour']; 
$cours_saisi      = $_POST['cours']; // Le cours qu'il veut suivre
$heure_actuelle   = date("H:i");

$message = ""; 



// --- DÉBUT DES CONDITIONS IMBRIQUÉES ---

// 1. VÉRIFIER SI L'AUDITOIRE EXISTE
if (isset($auditoires[$auditoire_saisi])) {
    $salle = $auditoires[$auditoire_saisi];

    // 2. BOUCLE POUR VÉRIFIER SI L'ÉTUDIANT EST ENREGISTRÉ DANS CET AUDITOIRE
    $etudiant_connu = false;
    foreach ($salle["etudiants"] as $etudiant) {
        if ($etudiant["matricule"] === $matricule_saisi) {
            $etudiant_connu = true;
            $_SESSION['nom_etudiant'] = $etudiant['nom']; // On garde son nom
            break; 
        }
    }

    if ($etudiant_connu) {
        // 3. VÉRIFIER SI LE COURS EXISTE POUR CE JOUR-LÀ cad on  verifi si le cour entrer fait partie du programme d'aujourd'hui
        $cours_trouve = false;
        foreach ($salle["horaire"] as $seance) {
            // On vérifie si le jour et le nom du cours correspondent
            // on verifi si le cours entrer existe et correspond ou s'il est donner le jour qu'elle a entrer
            if ($seance["jour"] === $jour_saisi && $seance["cours"] === $cours_saisi) {
                $cours_trouve = true;
                
                // 4. VÉRIFIER L'HEURE (Si le cours a débuté ou est fini)
                // on verifi l'heur d'acceé a l'auditoire pour le cour
                if ($heure_actuelle >= $seance["debut"] && $heure_actuelle <= $seance["fin"]) {
                    // $message = "✅ Accès accordé  à  ".  $_SESSION['nom_etudiant']  ." ! Bienvenue en cours de "
                    //  . $seance['cours'] .", veuiller acceder à l'auditoire";
                
                

                            // --- LOGIQUE DYNAMIQUE POUR LES 6 AUDITOIRES ---

                        // 1. On crée le nom du fichier selon l'auditoire choisi par l'étudiant
                        // Si l'étudiant a choisi "Auditoire 2", le fichier s'appellera "Auditoire 2.txt"
                        $nom_fichier = $auditoire_saisi . ".txt"; 

                        // 2. Vérifier si le fichier existe, sinon le créer
                        if (!file_exists($nom_fichier)) {
                            touch($nom_fichier);
                        }

                        // 3. Compter combien d'étudiants sont déjà dans CET auditoire précis
                        $lignes = file($nom_fichier);
                        $nombre_actuel = count($lignes);

                        // 4. Limite de 100 places
                        if ($nombre_actuel >= 100) {
                            $message = "❌ Accès Refusé : L' " . $auditoire_saisi . " est plein (100/100).";
                        } else {
                            // 5. Préparer les infos de l'étudiant
                            $infos = $_SESSION['nom_etudiant'] . " | " . $matricule_saisi . " | Entrée à : " . date("H:i") . PHP_EOL;

                            // 6. Enregistrer dans le bon fichier (celui de l'auditoire choisi)
                            file_put_contents($nom_fichier, $infos, FILE_APPEND);

                            // Message de succès avec le compteur
                            $place_occupee = $nombre_actuel + 1;
                            $message = "✅ Accès Autorisé à " . $_SESSION['nom_etudiant'] ." <br> Bienvenue dans l' " . $auditoire_saisi . " (Place " . $place_occupee . "/100)";
                        }





                 } else {
                    $message = "❌ Accès refusé : Ce cours n'est pas programmé à cette heure précise.";
                }
                break; // On a trouvé le cours, on arrête de chercher dans l'horaire
            }
        }

        if (!$cours_trouve) {
            // si le cour qu'elle a entrer ne pas programmer pour aujourdhuit on lui dit ca :
            $message = "❌ Accès refusé : Le cours de " . $cours_saisi . " n'est pas prévu le " . $jour_saisi;
        }

    } else {
        // si l'etudiant ne pas enregistrer dans l'auditoire on lui dit ca :
        $message = "❌ Accès refusé : Vous n'êtes pas sur la liste des étudiants de l' " . $auditoire_saisi;
    }

        // si cet auditoire n'existe pas on lui dit ca :
} else {
    $message = "❌ Erreur : Cet auditoire n'est pas répertorié dans notre système.";
}

// Affichage final du résultat
echo $message;











// Designe du message
if ($message !== "") {
    echo '
    <style>
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85); /* Fond très sombre */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .card {
            width: 320px;
            background-color: #2c313a; /* Couleur foncée du bas */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-header {
            background-color: #3e749e; /* Bleu Telegram de ta photo */
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-body {
            padding: 25px;
            color: #ffffff;
            font-size: 15px;
            line-height: 1.5;
            text-align: left;
        }
        .card-footer {
            padding: 10px 20px 20px;
            text-align: right;
        }
        .btn-continuer {
            color: #6fb1e4;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>

    <div class="overlay">
        <div class="card">
            <div class="card-header">
                <!-- Icône stylisée ressemblant à ta photo -->
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    <line x1="8" y1="16" x2="16" y2="16"></line>
                    <line x1="8" y1="18" x2="12" y2="18"></line>
                </svg>
            </div>
            <div class="card-body">
                ' . $message . '
            </div>
            <div class="card-footer">
                <a href="index.html" class="btn-continuer">Retourner</a>
            </div>
        </div>
    </div>';
}





?>