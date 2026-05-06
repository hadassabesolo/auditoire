


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: url("toph/710Jf-qM2bL._AC_UF894,1000_QL80_.jpg");
            
        }

        main{
            background-color:seashell;
        }

        input{
            background-color: slategrey;
        }

        
    </style>
</head>
<body>
    <main>
   <form action="logique.php" method="post">

            <label for="noms">Noms:</label>
            <input type="text" name="noms"  placeholder="nom" required>

            <label for="lieu">Matricule:</label>
            <input type="text" name="matricule"  placeholder="matricule" required>

            <label for="role">promotion</label>
            <input type="txt" name="promotion" placeholder="role" required>

            <label for="aud">numero de l'auditoire</label>
               <select type="text" name="aud">
                    <option value="Auditoire_1">auditoire_1</option>
                    <option value="Auditoire_2">auditoire_2</option>
                    <option value="Auditoire_3">auditoire_3</option>
                    <option value="Auditoire_4">auditoire_4</option>
                    <option value="Auditoire_5">auditoire_5</option>
                    <option value="Auditoire_6">auditoire_6</option>
                </select>

            
            <label for="aud">cours</label>
            <input type="text" name="cours" placeholder="cours" required>
            
            <label>Choisissez le jour :</label>
                <select type="text" name="jour">
                    <option value="lundi">lundi</option>
                    <option value="mardi">mardi</option>
                    <option value="mercredi">mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">vendredi</option>
                    <option value="samedi">samedi</option>
                </select>

            <button type="submit" class="btn">envoyer</button>

        </form> 
    </main>
</body>
</html>