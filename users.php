<?php
include 'conexioBDD.php';
// Only user-related functions
/*
        Function: generarApiKey()

            Funció que genera una api key a partir de valors aleatoris

        Parameters:

            $length - aquesta variable es la que diu de quants caràcters serà l'APIkey

        Returns:

            retorna l'apiKey generada

    */
function generarApiKey($length = 15) // Funciona OK
{
    //combinació de caràcters 0-9Aa
    $caracters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321';
    //guardo el nombre de caràcters
    $numCaracters = strlen($caracters);
    $apiKey = '';
    //faig un bucle on va escollint caracters aleatoris fins arribar a 15 caracters total
    for ($i = $length; $i > 0; $i--) {
        $apiKey .= $caracters[rand(0, $numCaracters - 1)];
    }
    //retorno l'apiKey
    return $apiKey;
}


/*
        Function: registerUser()

            Funcio que amb els paràmetres passats, els guarda a la taula d'usuaris creant un nou usuari

        Parameters:

            $UserName - nom d'usuari

            $UserLastName - cognom d'usuari
    
            $UserEmail - email d'usuari

            $passwd - password usuari
 
    */
function register_user($user_data)
{

    $user_name = $user_data["data"][0]["UserName"];
    $user_email = $user_data["data"][0]["UserEmail"];
    $user_pass = $user_data["data"][0]["pass"];
    $user_role = $user_data["data"][0]["UserRole"];
    $user_pic = $user_data["data"][0]["ProfilePic"];

    if (isset($user_name) && isset($user_email) && isset($user_pass) && isset($user_role)) {
        $baseDades = new BdD; //creo nova classe BDD

        try {
            $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $apiKey = generarApiKey();

            $sentenciaSQL = "INSERT INTO users (APIKEY, user_name, user_email, user_role, pass, profile_pic)
        VALUES (:APIKEY, :user_name, :user_email, :user_role, :passwd, :profile_pic);";

            $bdd = $conn->prepare($sentenciaSQL);
            $bdd->bindParam("APIKEY", $apiKey);
            $bdd->bindParam("user_name", $user_name);
            $bdd->bindParam("user_email", $user_email);
            $bdd->bindParam("user_role", $user_role);
            $bdd->bindParam("passwd", $user_pass);
            $bdd->bindParam("profile_pic", $user_pic);

            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

/*
        Function: selectOneUser()

            Funció que passant-li una API-KEY et retorna ture o flase si ha trobat un usuari o no amb aquesta

        Parameters:

            $UserID - Identificador d'aquell usuari

            $APIKEY - API-KEY d'aquell Usuari

        Returns:

            Retorna tota la informació d'aquell usuari

            Si no ha trobat cap usuari retornarà un missatge d'error
 
    */
function selectOneUser($APIKEY, $UserID)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM users WHERE `user_id` = :UserID AND `APIKEY` = :APIKEY";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("UserID", $UserID); //aplico els parametres necessaris
        $bdd->bindParam("APIKEY", $APIKEY);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat un usuari
            return $resultat;
        } else { //no ha trobat cap usuari
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        return false;
    }
}

/*
        Function: userValidation()

            Funció que passant-li una API-KEY et retorna ture o flase si ha trobat un usuari o no amb aquesta

        Parameters:

            $APIKEY - API-KEY de l'usuari 

        Returns:

            Retorna TRUE si ha trobat un usuari amb la API-KEY passada

            Retorna FALSE si no ha trobat cap usuari amb l'API-KEY passada o algo de la cosulta ha fallat
 
    */
function UserValidation($apikey, $userID, $user_role)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT user_id, APIKEY, user_role FROM users WHERE `APIKEY` = :APIKEY AND `user_id` = :UserID AND user_role = :user_role";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("APIKEY", $apikey); //aplico els parametres necessaris
        $bdd->bindParam("UserID", $userID); //aplico els parametres necessaris
        $bdd->bindParam("user_role", $user_role); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat un usuari
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
/*
        Function: selectAllUsers()

            Funcio que NOMES POT EXECUTAR L'USUARI ADMINISTRADOR i et selecciona tots els usuaris de la base de dades

        Parameters:

        Returns:

            Retorna tota la llista d'usuaris registrats
 
    */

function selectAllUsers($APIKEY, $UserID)
{
    if (selectOneUser($APIKEY, $UserID))
        $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM users";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        // si ha trobat un usuari
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        return false;
    }
}

/*
        Function: modifyUser()

            Funcio que amb els paràmetres passats, modifica l'usuari que tingui l'identificador passat

        Parameters:

            $UserName - nom d'usuari

            $UserLastName - cognom d'usuari
    
            $UserEmail - email d'usuari

            $passwd - password usuari

            $identificador - UserID

        Returns:

            Retorna true si el update s'ha fet correctament i false si algo ha fallat
 
    */
function updateUser($APIKEY, $UserID, $user_data, $role)
{
    $baseDades = new BdD; //creo nova classe BDD
    $user_name = $user_data["data"][0]["UserName"];
    $user_email = $user_data["data"][0]["UserEmail"];
    $user_pass = $user_data["data"][0]["pass"];
    $user_role = $user_data["data"][0]["UserRole"];
    $user_pic = $user_data["data"][0]["ProfilePic"];

    try {
        if (UserValidation($APIKEY, $UserID, $role)) { // Valido que l'usuari sigui el mateix
            $conn = new PDO("mysql:host={$baseDades->db_host};dbname={$baseDades->db_name}", $baseDades->db_user, $baseDades->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verifica si el correu electrònic ja existeix
            $checkEmailSQL = "SELECT COUNT(*) FROM users WHERE user_email = :user_email AND user_id != :UserID";
            $checkEmailStmt = $conn->prepare($checkEmailSQL);
            $checkEmailStmt->bindParam(":user_email", $user_email);
            $checkEmailStmt->bindParam(":UserID", $UserID);
            $checkEmailStmt->execute();
            $emailCount = $checkEmailStmt->fetchColumn();

            if ($emailCount > 0) {
                header('HTTP/1.1 409 Conflict');
                echo "This email is already taken by another user";
                return false;
            }

            // Actualització de l'usuari
            $sentenciaSQL = "UPDATE users
            SET user_name = :user_name, user_email = :user_email, user_role = :user_role, pass = :pass, profile_pic = :profile_pic
            WHERE user_id = :UserID";

            $bdd = $conn->prepare($sentenciaSQL);
            $bdd->bindParam(":user_name", $user_name);
            $bdd->bindParam(":user_email", $user_email);
            $bdd->bindParam(":user_role", $user_role);
            $bdd->bindParam(":pass", $user_pass);
            $bdd->bindParam("profile_pic", $user_pic);
            $bdd->bindParam("UserID", $UserID);

            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);

            return true;
        } else { //retorna aixo si un usuari amb id 5 intenta modificar un altre amb
            echo "this user is not yours, cannot modify it ";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


/*
        Function: LogIn()

            Funció que passant-li un usuari i una contrassenya et retorna el seu ID i API-KEY

        Parameters:

            $UserName - Variable amb el valor del correu de l'usuari

            $passwd - Variable amb la contrassenya de l'usuari

        Returns:

            Retorna l'Ud d'usuari i la seva API-KEY

    */
function LogIn($user_data)
{

    //var_dump($user_data);
    $baseDades = new BdD; //creo nova classe BDD
    $user_email = $user_data["data"][0]["UserEmail"];
    $user_pass = $user_data["data"][0]["pass"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT user_id, APIKEY, user_role, profile_pic, user_name FROM users WHERE `user_email` = :user_email AND `pass` = :pass";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("user_email", $user_email); //aplico els parametres necessaris
        $bdd->bindParam("pass", $user_pass);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat un usuari
            return $resultat;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        return false;
    }

    function DeleteUser($user_id){
        $baseDades = new BdD; //creo nova classe BDD

        try {
            $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $senteciSQL = "DELETE FROM users user_id = :user_id";
    
            $bdd = $conn->prepare($senteciSQL);
            $bdd->bindParam("user_id", $user_id); //aplico els parametres necessaris
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $bdd->fetchAll(); //guardo els resultats
    
             // si ha trobat un usuari
                return true;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
    
            return false;
        }
    }
}
