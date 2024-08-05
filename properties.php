<?php

function addProperty($category_dades, $User_id)
{

    $baseDades = new BdD; //creo nova classe BDD

    $category_name = $category_dades["data"][0]["CategoryName"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentenciaSQL = "INSERT INTO properties (property_name, format)
                    VALUES (:categoryName, :user_id);";

        $bdd = $conn->prepare($sentenciaSQL);
        $bdd->bindParam("property_name", $category_name);
        $bdd->bindParam("format", $User_id);

        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getAllProperties()
{

    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM properties";

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
