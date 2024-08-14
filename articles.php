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
    var_dump($articlePropsId);
    var_dump($articlePosition);
    var_dump($articlePropsVal);

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
                $sentenciaArticlesProps =
                    "
                INSERT INTO propertiesxarticles (article_value, position, property_id, article_id)
                VALUES (:article_value, :position, :property_id, :article_id);
                ";
                $bdd = $conn->prepare($sentenciaArticlesProps);
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
function modifyArticle($articleDades, $user_id, $articleID, $userRole)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //comprovar de que el component sigui de l'usuari
        $senteciaVerificacioArticleUser =
            "
            SELECT user_id FROM `articles` where article_id = :article_id;
            ";
        $bdd = $conn->prepare($senteciaVerificacioArticleUser);
        $bdd->bindParam("article_id", $articleID); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $UserArtID = $bdd->fetchAll(); //guardo els resultats
        $UserArtIDBaseDades = $UserArtID[0]['user_id'];
        // var_dump($UserArtIDBaseDades);

        //Comprovem de que el userID passat per parametre sigui el mateix usuariID que el del component de la BDD que es vol modificar
        if ($user_id == $UserArtIDBaseDades || $userRole == "admin") {
            //extreiem la informació que actualitzarem a la taula de components

            $articleVisibility = $articleDades['data']['visibility'];
            $articleTitle = $articleDades['data']['article_title'];
            $articleDescription = $articleDades['data']['descripcio'];
            $articleStatus = $articleDades['data']['article_status'];
            $articleCategoryID = $articleDades['data']['category_id'];

            // var_dump($articleDescription);

            $articlePropsId = []; //array on guardo tots els IDs de les propiestats de cada article
            $articlePropsVal = []; //array on vaig guardant els valors de les propietats dels components
            $articlePosition = []; //array on hi vaig guardant els IDs de les propietats dels components
            $idPKPXA = []; //vaig guardant totes les claus primàries de la taula componentxproperties

            $dataArrayLength = sizeof($articleDades['data']['props']);

            for ($i = 0; $i < $dataArrayLength; $i++) { //Bucle on vaig guardant cada informació al seu lloc
                array_push($articlePropsVal, $articleDades['data']['props'][$i]['prop_val']);
                array_push($articlePropsId, $articleDades['data']['props'][$i]['prop_id']);
                array_push($articlePosition, $articleDades['data']['props'][$i]['position']);
            }

            // //Extrect totes les claus primàries dels registres dels valors del component(PKPXC)
            $senteciaExtreurePKPXA =
                "
            SELECT PKPXA 
            FROM `propertiesxarticles`
            WHERE article_id = :article_id;
            ";
            $bdd = $conn->prepare($senteciaExtreurePKPXA);
            $bdd->bindParam("article_id", $articleID);
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);
            $resultatPKPXA = $bdd->fetchAll(); //guardo els resultats

            // aixi saber quines posicions haig de modificar i que tots els registres no acabin amb el mateix valor
            for ($e = 0; $e < sizeof($resultatPKPXA); $e++) {
                array_push($idPKPXA, $resultatPKPXA[$e]['PKPXA']);
            }

            //Actualitzo els valors a la taula components
            $sentenciaUpdateTArticles =
                "
                UPDATE articles
                SET visibility = :visibility, article_title = :article_title, descripcio = :descripcio, article_status = :article_status, category_id = :category_id
                WHERE article_id = :article_id;
            ";

            $bdd = $conn->prepare($sentenciaUpdateTArticles);
            $bdd->bindParam("visibility", $articleVisibility);
            $bdd->bindParam("article_title", $articleTitle);
            $bdd->bindParam("descripcio", $articleDescription);
            $bdd->bindParam("article_status", $articleStatus);
            $bdd->bindParam("category_id", $articleCategoryID);
            $bdd->bindParam("article_id", $articleID);
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);
            $resultatTC = $bdd->fetchAll(); //guardo els resultats



            // echo "entra";
            for ($a = 0; $a < $dataArrayLength; $a++) {
                $sentenciaTArticlesProps =
                    "
                        UPDATE `propertiesxarticles`
                        SET article_value = :article_value, position = :position
                        WHERE PKPXA = :PKPXA AND article_id = :article_id;
                    ";

                $bdd = $conn->prepare($sentenciaTArticlesProps);
                $bdd->bindParam("article_value", $articlePropsVal[$a]); //aplico els parametres necessaris
                $bdd->bindParam("position", $articlePosition[$a]);
                $bdd->bindParam("PKPXA", $idPKPXA[$a]);
                $bdd->bindParam("article_id", $articleID);
                $bdd->execute(); //executola sentencia
                // $bdd->setFetchMode(PDO::FETCH_ASSOC);
                // $resultatCompID = $bdd->fetchAll(); //guardo els resultats
            }
            // si l'actualització del component s'ha fet correctament
            return true;
        } else { //si el component és d'un altre usuari
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteArticle($articleID, $user_id, $userRole)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //varificar de que el IDusuari passat per paràmetre sigui el mateix 
        //comprovar de que el component sigui de l'usuari
        $senteciaVerificacioArticleUser =
            "
        SELECT user_id FROM `articles` where article_id = :article_id;
        ";
        $bdd = $conn->prepare($senteciaVerificacioArticleUser);
        $bdd->bindParam("article_id", $articleID); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $UserArtID = $bdd->fetchAll(); //guardo els resultats
        $UserArtIDBaseDades = $UserArtID[0]['user_id'];
        // var_dump($UserArtIDBaseDades);

        //Comprovem de que el userID passat per parametre sigui el mateix usuariID que el del component de la BDD que es vol modificar
        if ($user_id == $UserArtIDBaseDades || $userRole == "admin") {
            //anar eliminant les taules, Començaré per propertiesxarticles i despres el registre de la taula articles
            $sentenciaDeleteTAXP =
                "
            DELETE FROM `propertiesxarticles` WHERE article_id = :article_id;
            ";
            $bdd = $conn->prepare($sentenciaDeleteTAXP);
            $bdd->bindParam("article_id", $articleID); //aplico els parametres necessaris
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);

            $sentenciaDeleteTArticles =
                "
            DELETE FROM `articles` WHERE article_id = :article_id;
            ";
            $bdd = $conn->prepare($sentenciaDeleteTArticles);
            $bdd->bindParam("article_id", $articleID); //aplico els parametres necessaris
            $bdd->execute(); //executola sentencia
            $bdd->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

//funció per seleccionar TOT un article
function getOneArticle($article_id)
{
    $baseDades = new BdD; //creo nova classe BDD

    try {
        $conn = new PDO("mysql:host=$baseDades->db_host;dbname=$baseDades->db_name", $baseDades->db_user, $baseDades->db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //faig una sentencia on selecciono per cada propietat el id de tipus, nom tipus component, idPropietat, nomPropietat i propietat tipus fent 2 joins, un a cada taula on haig d'extreure la informacio
        $sentencia = "
            SELECT articles.user_id, articles.article_pic, articles.visibility, articles.article_title, articles.descripcio, articles.article_status, 
            articles.category_id, articles.user_name, articles.article_id, properties.property_id,properties.property_name, 
            propertiesxarticles.article_value, propertiesxarticles.position
            FROM propertiesxarticles
            RIGHT JOIN articles ON propertiesxarticles.article_id =  articles.article_id
            LEFT JOIN properties ON propertiesxarticles.property_id = properties.property_id
            WHERE articles.article_id = :article_id;
            ";

        $bdd = $conn->prepare($sentencia);
        $bdd->bindParam("article_id", $article_id); //aplico els parametres necessaris
        $bdd->execute(); //executola sentencia
        $bdd->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $bdd->fetchAll(); //guardo els resultats

        $userIdArticle = $resultat[0]['user_id'];
        $articleVisibility = $resultat[0]['visibility'];
        $articleTitle = $resultat[0]['article_title'];
        $articleDescription = $resultat[0]['descripcio'];
        $articleStatus = $resultat[0]['article_status'];
        $articleCategoryId = $resultat[0]['category_id'];
        $articleUserName = $resultat[0]['user_name'];
        $articleID = $resultat[0]['article_id'];
        $articlePic = $resultat[0]['article_pic'];

        //carrego dos arrays buits 
        $arrAllArts = []; //en aquest hi guardaré tota la informació de cada component que hi vagi guardant(array general)
        $arrArtProp = []; //en aquest hi guardare totes les propietats de cada component
        $arrIdsArtticles = []; //array unicament d'IDs de component

        foreach ($resultat as $i) { //aquest foreach serveix unicament per a guardar els IDs de cada component

            $idArt = $i['article_id']; //agafo l'id de component

            if (!in_array($idArt, $arrIdsArtticles)) { //comprovo de que el id no estigui repetit, in_array ens diu si el valor passat està a dins de l'array
                array_push($arrIdsArtticles, $idArt); //si no esta repetit, el guardo
            }
        }

        $lenId = sizeof($arrIdsArtticles); //aquest lenID de lengthID, ens guarda la longitud de tots els IDs guardats 
        for ($i = 0; $i < $lenId; $i++) { //iterem tots els IDs 

            $arrayTotUnArticle = [];
            $arrArtProp = [];

            foreach ($resultat as $el) { //iterem tota la consulta(ja que hi han moltes files amb el mateix componentID)

                $idArt = $el['article_id']; //ectrec el idComponent de l'iteració del moment
                if ($idArt == $arrIdsArtticles[$i]) { //si el idCom és igual al valor de la posició de l'array d'IDs

                    $arrayProp = array( //guarden les propietats del ID trobat a l'array indexat
                        "prop_id" => $el['property_id'],
                        "prop_val" => $el['article_value'],
                        "position" => $el['position']
                    );
                    array_push($arrArtProp, $arrayProp); //guardem les propietats de cada component
                }
            }
            
            $arrayTotUnArticle = array( //agafo les propietats no variables de cada component a l'array indexat i els hi aplico
                "article_id" => $arrIdsArtticles[$i],
                "user_id" => $userIdArticle,
                "visibility" => $articleVisibility,
                "category_id" => $articleCategoryId,
                "article_user_name" => $articleUserName,
                "article_title" => $articleTitle,
                "descripcio" => $articleDescription,
                "article_status" => $articleStatus,
                "article_pic" => $articlePic,
                "props" => $arrArtProp
            );
            array_push($arrAllArts, $arrayTotUnArticle); //aquest array.push representa un push de tot un component
        }

        // var_dump($arrAllArts);
        // echo json_encode($arrAllArts);
            return $arrAllArts;
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        return false;
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
