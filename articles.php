<?php

function generarCodiArticle($length = 25) // Funciona OK
{
    //combinació de caràcters 0-9Aa
    $caracters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321';
    //guardo el nombre de caràcters
    $numCaracters = strlen($caracters);
    $article_code = '';
    //faig un bucle on va escollint caracters aleatoris fins arribar a 15 caracters total
    for ($i = $length; $i > 0; $i--) {
        $article_code .= $caracters[rand(0, $numCaracters - 1)];
    }
    //retorno l'article_code
    return $article_code;
}



function createArticle($articleDades, $userID)
{
    // var_dump($articleDades);
    //separo les dades a inserir a la taula Components
    $articleVisibility = $articleDades['data']['visibility'];
    $articleTitle = $articleDades['data']['article_title'];
    $articleDescription = $articleDades['data']['descripcio'];
    $articleStatus = $articleDades['data']['article_status'];
    $articleCategoryID = $articleDades['data']['category_id'];

    $articlePropsId = []; //array on guardo tots els IDs de les propiestats de cada article
    $articlePropsVal = []; //array on vaig guardant els valors de les propietats dels components
    $articlePosition = []; //array on hi vaig guardant els IDs de les propietats dels components

    $dataArrayLength = sizeof($articleDades['data']['props']);

    for ($i = 0; $i < $dataArrayLength; $i++) { //Bucle on vaig guardant cada informació al seu lloc
        array_push($articlePropsVal, $articleDades['data']['props'][$i]['prop_val']);
        array_push($articlePropsId, $articleDades['data']['props'][$i]['prop_id']);
        array_push($articlePosition, $articleDades['data']['props'][$i]['position']);
    }

    $articleCode = generarCodiArticle();

    $baseDades = new BdD;
    //!!! Primer insert a la taula articles, inserció de la informació general

    try {
        $boolEstat = true;
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentenciaTarticles =
            "
        INSERT INTO articles (visibility, article_title, descripcio, article_status, user_id, category_id, article_code)
        VALUES (:visibility, :article_title, :descripcio, :article_status, :user_id, :category_id, :article_code);
        ";

        $bdd = $conn->prepare($sentenciaTarticles);
        $bdd->bindParam("visibility", $articleVisibility); //aplico els parametres necessaris
        $bdd->bindParam("article_title", $articleTitle);
        $bdd->bindParam("descripcio", $articleDescription);
        $bdd->bindParam("article_status", $articleStatus);
        $bdd->bindParam("user_id", $userID);
        $bdd->bindParam("category_id", $articleCategoryID);
        $bdd->bindParam("article_code", $articleCode);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultatTC = $bdd->fetchAll(); //guardo els resultats

        if ($boolEstat == true) { //si la inserció s'ha fet correctament, farem un select del component recent incerit
            $senteciaCompID =
                "
            SELECT article_id
            FROM articles 
            WHERE user_id = :user_id AND article_code = :article_code;
            ";
            $bdd = $conn->prepare($senteciaCompID);
            $bdd->bindParam("user_id", $userID); //aplico els parametres necessaris
            $bdd->bindParam("article_code", $articleCode);
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);
            $resultatArtID = $bdd->fetchAll(); //guardo els resultats
            $articleID = $resultatArtID[0]['article_id'];

            for ($a = 0; $a < $dataArrayLength; $a++) {
                $sentenciaCompProps =
                    "
                INSERT INTO propertiesxarticles (article_value, position, property_id, article_id)
                VALUES (:article_value, :position, :property_id, :article_id);
                ";
                $bdd = $conn->prepare($sentenciaCompProps);
                $bdd->bindParam("article_value", $articlePropsVal[$a]); //aplico els parametres necessaris
                $bdd->bindParam("position", $articlePosition[$a]);
                $bdd->bindParam("property_id", $articlePropsId[$a]);
                $bdd->bindParam("article_id", $articleID);
                $bdd->execute(); //executola sentencia
                $bdd->setFetchMode(PDO::FETCH_ASSOC);
                $resultatCompID = $bdd->fetchAll(); //guardo els resultats
            }
            return $boolEstat; //ha anat tot bé
        } else {
            //algo ha fallat
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

//Funcions de modificar i eliminar articles()
function updateArticle()
{
}

function deleteArticle()
{
}

//funció per seleccionar TOT un article
function getOneArticle($article_id)
{
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM articles WHERE `article_id` = :article_id";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->bindParam("article_id", $article_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// funcions de selecció d'artices simples
function getAllArticles()
{ // fer condicionals per controlar els articles publics i privats
    $baseDades = new BdD; //creo nova classe BDD
    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $senteciSQL = "SELECT * FROM articles";

        $bdd = $conn->prepare($senteciSQL);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats
        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getArticlesBySearch($searchWord)
{
    $searchValue = "%" . $searchWord . "%";

    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentencia = "
        SELECT *
        FROM articles
        WHERE article_title LIKE :SearchValue2 
        ORDER BY article_id ASC;
        ";

        $bdd = $conn->prepare($sentencia);
        $bdd->bindParam("SearchValue2", $searchValue);
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        return $resultat;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
