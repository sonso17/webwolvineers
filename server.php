<?php
include 'users.php';

class Server
{
    /*
  Function: serve()
    Funcio que retalla la URL i agafa els valors per després dirigir a la funcionalitat de l'API adequada
  */
    public function serve()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $paths = explode('/', $uri);
        array_shift($paths); 
        array_shift($paths);
        $recurs1 = array_shift($paths);
        $recurs2 = array_shift($paths);
        $identificador = array_shift($paths);

        // http://wolvineers/API/recurs1/recurs2/identificador
        // public part ex. http://wolvineers/API/register
        // public part ex. http://wolvineers/API/article/1
        //                                                                    //recurs1 //recurs2

        // private part ex. http://wolvineers/API/API-KEY/modifyUser/1
        //                                                               //recurs1  recurs2   identificador

        if ($method == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit;
        }
        //aqui anar posant endpoints publics

        if ($recurs1 == "register") {
            if ($method == 'POST') // validem que sigui per GET
            { //agafo tota la info de l'usuari en JSON
                $put = json_decode(file_get_contents('php://input'), true);
                
                $message = register_user($put);

                if ($message == true) {
                    echo "user inserted correctly";
                    header('HTTP/1.1 200 OK');
                } else {
                    echo "user registration failed";
                    header('HTTP/1.1 417 EXPECTATION FAILED');
                }
                
            } else { //si el mètode es qualsevol altre cosa que POST
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } 
    }
}

$server = new Server;
$server->serve();
?>


