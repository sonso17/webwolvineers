<?php
/*
        Function: CreateCategory()

            Funcio que amb els paràmetres passats, crea una nova categoria a la abse de dades

        Parameters:

            $CategoryName - nom categoria

            $userID - identificador d'usuari
    
 
    */

function CreateCategory($category_dades, $User_id)
{
    $baseDades = new BdD; //creo nova classe BDD

    $category_name = $category_dades["data"][0]["CategoryName"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentenciaSQL = "INSERT INTO categories (category_name, user_id)
                VALUES (:categoryName, :user_id);";

        $bdd = $conn->prepare($sentenciaSQL);
        $bdd->bindParam("categoryName", $category_name);
        $bdd->bindParam("user_id", $User_id);

        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


/*
        Function: ModifyCategory()

            Funcio que amb els paràmetres passats, modifica una categoria ja existent

        Parameters:

            $CategoryName - nom categoria

            $userID - identificador d'usuari
    
 
    */

function ModifyCategory($category_dades, $User_id, $category_id, $role)
{
    $baseDades = new BdD; //creo nova classe BDD

    $category_name = $category_dades["data"][0]["CategoryName"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT user_id FROM categories WHERE `category_id` = :category_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("category_id", $category_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        if (count($resultat) == 1) { // si ha trobat una categoria existent
            // verificarem que l'usuari que modifica la categoria sigui el mateix que el propietari de la categoria
            $user_id_category = $resultat[0]["user_id"];

            if ($user_id_category == $User_id || $role == "Admin") {
                $sentenciaSQL = "UPDATE categories
            SET category_name = :category_name WHERE category_id = :category_id";

                $bdd = $conn->prepare($sentenciaSQL);
                $bdd->bindParam(":category_name", $category_name);
                $bdd->bindParam(":category_id", $category_id);

                $bdd->execute(); //executola sentencia
                $bdd->setFetchMode(PDO::FETCH_ASSOC);

                return true;
            } else {
                echo "this category does not belong to you!";
            }
        } else { //no ha trobat cap categoria
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

/*
        Function: GetOneCategory()

            Funció que et selecciona una categoria en concret a partir del seu category_id

        Parameters:

            $Category_id - identificador de categoria

 
    */

function GetOneCategory($category_id)
{
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM categories WHERE `category_id` = :category_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("category_id", $category_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
/*
        Function: GetAllCategory()

            Funció que et selecciona totes les categories

        Parameters:

            

 
    */
function GetAllCategories(){
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM categories";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
