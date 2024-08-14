<?php

function CreateComentari($comentariInfo){
    $baseDades = new BdD; //creo nova classe BDD

    $comentari_text = $comentariInfo["data"][0]["comment_text"];
    $article_id = $comentariInfo["data"][0]["article_id"];

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentenciaSQL = "INSERT INTO comentaris (coment_text, article_id)
                VALUES (:comentari_text, :article_id);";

        $bdd = $conn->prepare($sentenciaSQL);
        $bdd->bindParam("comentari_text", $comentari_text);
        $bdd->bindParam("article_id", $article_id);

        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function GetComentsByArticle($articleID){
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM comentaris WHERE `article_id` = :article_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("article_id", $articleID); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}