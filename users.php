<?php
include 'conexioBDD.php';
// Only user-related functions

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

function saltAndPepper($input, $salt, $pepper) {
    $peppered = hash_hmac('sha256', $input, $pepper);
    $salted = hash_hmac('sha256', $salt, $peppered);
    return $salted;
}

function register_user($user_data)
{
    
    $user_name = $user_data["data"][0]["UserName"];
    $user_email = $user_data["data"][0]["UserEmail"];
    $user_pass = $user_data["data"][0]["pass"];
    $user_role = $user_data["data"][0]["UserRole"];
    $user_pic = $user_data["data"][0]["ProfilePic"];

    

    if (isset($user_name) && isset($user_email) && isset($user_pass) && isset($user_role))
    {
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
?>