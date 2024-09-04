<?php
/*
        Function: createPaetron()

            Funcio que amb els paràmetres passats, crea un nou patrocinador i el garda a la base de dades

        Parameters:

            $paetron_name - nom categoria

            $paetron_logo - identificador d'usuari

            $paetron_link - enllaç a la web del patrocinador

            $user_id - identificador de l'usuari
    
 
    */
function createPaetron($paetron_data, $user_id)
{

    $paetron_name = $paetron_data["data"][0]["paetron_name"];
    $paetron_logo = $paetron_data["data"][0]["paetron_logo"];
    $paetron_link = $paetron_data["data"][0]["paetron_link"];

    // var_dump($paetron_link,$paetron_logo,$paetron_name);
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentenciaSQL = "INSERT INTO paetrons (paetron_name, paetron_logo, paetron_link, user_id)
                VALUES (:paetron_name, :paetron_logo, :paetron_link, :user_id);";

        $bdd = $conn->prepare($sentenciaSQL);
        $bdd->bindParam("paetron_name", $paetron_name);
        $bdd->bindParam("paetron_logo", $paetron_logo);
        $bdd->bindParam("paetron_link", $paetron_link);
        $bdd->bindParam("user_id", $user_id);

        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


/*
        Function: ModifyPaetron()

            Funcio que amb els paràmetres passats, modifica un patrocinador ja existent

        Parameters:

            $paetron_name - nom categoria

            $paetron_logo - identificador d'usuari

            $paetron_link - enllaç a la web del patrocinador

            $user_id - identificador de l'usuari
    
 
    */

function ModifyPaetron($paetron_data, $User_id, $paetron_id, $role)
{
    $baseDades = new BdD; //creo nova classe BDD

    $paetron_name = $paetron_data["data"][0]["paetron_name"];
    $paetron_logo = $paetron_data["data"][0]["paetron_logo"];
    $paetron_link = $paetron_data["data"][0]["paetron_link"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT user_id FROM paetrons WHERE `paetron_id` = :paetron_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("paetron_id", $paetron_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat un patrocinador existent
            // verificarem que l'usuari que modifica la categoria sigui el mateix que el propietari de la categoria

            $user_id_paetron = $resultat[0]["user_id"];

            if ($user_id_paetron == $User_id || $role == "Admin") {
                $sentenciaSQL = "UPDATE paetrons
                SET paetron_name = :paetron_name, paetron_logo = :paetron_logo, paetron_link = :paetron_link 
                WHERE paetron_id = :paetron_id";

                $bdd = $conn->prepare($sentenciaSQL);
                $bdd->bindParam(":paetron_name", $paetron_name);
                $bdd->bindParam(":paetron_logo", $paetron_logo);
                $bdd->bindParam(":paetron_link", $paetron_link);
                $bdd->bindParam(":paetron_id", $paetron_id);

                $bdd->execute(); //executola sentencia
                $bdd->setFetchMode(PDO::FETCH_ASSOC);

                return true;
            } else {
                echo "this paetron does not belong to you!";
            }
        } else { //no ha trobat cap patrocinador
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


/*
        Function: GetOnePaetron()

            Funció que et selecciona un patrocinador i et retorna tota la seva informació

        Parameters:

            $paetron_id - identificador del patrocinador

 
    */

function GetOnePaetron($paetron_id)
{
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM paetrons WHERE `paetron_id` = :paetron_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("paetron_id", $paetron_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

/*
        Function: GetAllPaetrons()

            Funció que et selecciona tots els patrocinadors registrats

        Parameters:

            *

 
    */

function GetAllPaetrons()
{
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM paetrons";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


/*
        Function: DeletePaetron()

            Funció que elimina a un patrocinador a partir del seu identificador

        Parameters:

            - $paetron_id - identificador del patrocinador

 
    */

function deletePaetron($paetron_id, $User_id, $role)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT user_id FROM paetrons WHERE `paetron_id` = :paetron_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("paetron_id", $paetron_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat un patrocinador existent
            // verificarem que l'usuari que elimina el patrocinador sigui el mateix que el propietari del patrocinador

            $user_id_paetron = $resultat[0]["user_id"];

            if ($user_id_paetron == $User_id || $role == "Admin") {
                $sentenciaSQL = "DELETE FROM paetrons where `paetron_id` = :paetron_id";

                $bdd = $conn->prepare($sentenciaSQL);
                $bdd->bindParam(":paetron_id", $paetron_id);

                $bdd->execute(); //executola sentencia
                $bdd->setFetchMode(PDO::FETCH_ASSOC);

                return true;
            } else {
                echo "this paetron does not belong to you!";
            }
        } else { //no ha trobat cap patrocinador
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}
