<?php
include 'users.php';
include 'articles.php';
include 'categories.php';
include 'paetrons.php';
include 'properties.php';
include 'comentaris.php';

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

        if ($recurs1 == "register") { //posat
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
        } else if ($recurs1 == "LogIn") {//posat
            if ($method == 'POST') // validem que sigui per GET
            { //agafo tota la info de l'usuari en JSON
                $put = json_decode(file_get_contents('php://input'), true);

                $message = LogIn($put);

                if ($message == true) {
                    // echo "user inserted correctly";
                    echo json_encode($message);
                    header('HTTP/1.1 200 OK');
                } else {
                    echo "username or password not correct";
                    header('HTTP/1.1 417 EXPECTATION FAILED');
                }
            } else { //si el mètode es qualsevol altre cosa que POST
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } else if ($recurs1 == "GetOneCategory") {
            if ($method == "GET") {
                if ($recurs2 != "") { //si hi ha un identificador de categoria
                    $put = json_decode(file_get_contents('php://input'), true);
                    echo json_encode(GetOneCategory($recurs2)); //li passo l'api-key i el UserID
                    header('HTTP/1.1 200 OK');
                } else {
                    header('HTTP/1.1 417 EXPECTATION FAILED');
                    echo "category identifier needed";
                }
            } else {
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } else if ($recurs1 == "GetAllCategories") {
            if ($method == "GET") {
                $put = json_decode(file_get_contents('php://input'), true);
                echo json_encode(GetAllCategories());
                header('HTTP/1.1 200 OK');
            } else {
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } else if ($recurs1 == "GetOnePaetron") {
            if ($method == "GET") {
                if ($recurs2 != "") { //si hi ha un identificador de categoria
                    $put = json_decode(file_get_contents('php://input'), true);
                    echo json_encode(GetOnePaetron($recurs2)); //li passo l'api-key i el UserID
                    header('HTTP/1.1 200 OK');
                } else {
                    header('HTTP/1.1 417 EXPECTATION FAILED');
                    echo "paetron identifier needed";
                }
            } else {
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } else if ($recurs1 == "GetAllPaetrons") {
            if ($method == "GET") {
                // $put = json_decode(file_get_contents('php://input'), true);
                echo json_encode(GetAllPaetrons());
                header('HTTP/1.1 200 OK');
            } else {
                header('HTTP/1.1 405 Method Not Allowed');
            }
        } else {
            $id = explode('.', $recurs1); //divideixo el valor passat del recurs1(apikey + userID + rol)

            $apikey = $id[0];
            $userID = $id[1];
            $role = $id[2];


            if (UserValidation($apikey, $userID, $role) == true) {
                /*----------------------------------------------------------USERS---------------------------------------------------------- */
                if ($recurs2 == "UserInfo") {
                    if ($method == "GET") {
                        if ($identificador != "") { //si hi ha un identificador d'usuari
                            echo json_encode(selectOneUser($apikey, $identificador)); //li passo l'api-key i el UserID
                            header('HTTP/1.1 200 OK');
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "User identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "GetAllUsers") // MILLORAR CONDICIO DE CARES A FUTUR(AQUESTA NOMES POT ACCEDIR ROOT)
                {
                    if ($method == "GET") {
                        if ($identificador != "") { //si hi ha un identificador d'usuari
                            echo json_encode(selectAllUsers($apikey, $identificador)); //li passo l'api-key i el UserID
                            header('HTTP/1.1 200 OK');
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "User identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "ModifyUser") {
                    if ($method == "POST") {
                        if ($identificador != "") { //si hi ha un identificador d'usuari
                            $put = json_decode(file_get_contents('php://input'), true);

                            $message = updateUser($apikey, $userID, $put, $role);

                            if ($message == true) {
                                echo "user modified correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "user modification failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "User identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeleteUser") {

                    if ($identificador != "") {
                        $missatge = DeleteUser($userID, $role);

                        if ($missatge == true) {
                            echo "User deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "User deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    }
                }
                else if ($recurs2 == "CreateCategory") {
                    if ($method == "POST") {
                        $put = json_decode(file_get_contents('php://input'), true);
                        $message = CreateCategory($put, $userID);

                        if ($message == true) {
                            echo "Category created correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "category creation failed";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    } else {
                        header('HTTP/1.1 417 EXPECTATION FAILED');
                        echo "Wrong method";
                    }
                } else if ($recurs2 == "ModifyCategory") {
                    if ($method == "POST") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            $put = json_decode(file_get_contents('php://input'), true);

                            $message = ModifyCategory($put, $userID, $identificador, $role);

                            if ($message == true) {
                                echo "category modified correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "category modification failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "category identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                }
               else if ($recurs2 == "DeleteCategory") {
                    /*DeleteCategory */
                    echo "Delete Category";
                }
                else if ($recurs2 == "CreatePaetron") {
                    if ($method == "POST") {
                        $put = json_decode(file_get_contents('php://input'), true);
                        // var_dump($put);
                        $missatge = createPaetron($put, $userID);

                        if ($missatge == true) {
                            echo "Paetron created correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "paetron creation failed";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "ModifyPaetron") {
                    if ($method == "POST") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            $put = json_decode(file_get_contents('php://input'), true);

                            $message = ModifyPaetron($put, $userID, $identificador, $role);

                            if ($message == true) {
                                echo "paetron modified correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "paetron modification failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "paetron identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeletePaetron") {
                    if ($identificador != "") {
                        $missatge = deletePaetron($identificador, $userID, $role);

                        if ($missatge == true) {
                            echo "paetron deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "paetron deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    }
                } else if ($recurs2 == "CreateProperty") {
                    if ($method == "POST") {
                        $put = json_decode(file_get_contents('php://input'), true);
                        // var_dump($put);
                        $missatge = addProperty($put, $userID);

                        if ($missatge == true) {
                            echo "Paetron created correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "paetron creation failed";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "ModifyProperty") {
                    if ($method == "POST") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            $put = json_decode(file_get_contents('php://input'), true);

                            $message = ModifyPaetron($put, $userID, $identificador, $role);

                            if ($message == true) {
                                echo "paetron modified correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "paetron modification failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "paetron identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "GetOneProperty") {
                    if ($method == "GET") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            $put = json_decode(file_get_contents('php://input'), true);
                            echo json_encode(GetOnePaetron($identificador)); //li passo l'api-key i el UserID
                            header('HTTP/1.1 200 OK');
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "category identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "GetAllProperties") {
                    if ($method == "GET") {
                        // $put = json_decode(file_get_contents('php://input'), true);
                        echo json_encode(GetAllPaetrons());
                        header('HTTP/1.1 200 OK');
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeleteProperty") {
                    if ($identificador != "") {
                        $missatge = deletePaetron($identificador, $userID, $role);

                        if ($missatge == true) {
                            echo "paetron deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "paetron deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    }
                } else if ($recurs2 == "CreateArticle") {
                    if ($method == "POST") {
                        $put = json_decode(file_get_contents('php://input'), true);
                        // var_dump($put);
                        $missatge = createArticle($put, $userID);

                        if ($missatge == true) {
                            echo "Article created correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "Article creation failed";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    }
                } else if ($recurs2 == "SelectAllArticles") {
                    if ($method == "GET") {
                        // $put = json_decode(file_get_contents('php://input'), true);
                        echo json_encode(getAllArticles());
                        header('HTTP/1.1 200 OK');
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "SelectOneArticle") {
                    if ($method == "GET") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            // $put = json_decode(file_get_contents('php://input'), true);
                            echo json_encode(getOneArticle($identificador, $userID)); //li passo l'api-key i el UserID
                            header('HTTP/1.1 200 OK');
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "article identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "SelectArticlesBySearch") {
                    if ($method == "POST") {
                        $put = json_decode(file_get_contents('php://input'), true);
                        $searchWord = $put["data"]["SearchWord"];
                        // var_dump($searchWord);
                        echo json_encode(getArticlesBySearch($searchWord));
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "ModifyArticle") {
                    if ($method == "POST") {
                        if ($identificador != "") { //si hi ha un identificador de categoria
                            $put = json_decode(file_get_contents('php://input'), true);

                            $message = modifyArticle($put, $userID, $identificador, $role);

                            if ($message == true) {
                                echo "article modified correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "article modification failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "article identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeleteArticle") {
                    if ($identificador != "") {
                        $missatge = deleteArticle($identificador, $userID, $role);

                        if ($missatge == true) {
                            echo "article deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "article deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    }
                } else if ($recurs2 == "SelectPublicArticles") {
                    if ($method == "GET") {

                        echo json_encode(GetPublicArticles());
                        header('HTTP/1.1 200 OK');
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "SelectUserArticles") {
                    if ($method == "GET") {
                        if ($userID != "") { //si hi ha un identificador de categoria
                            // $put = json_decode(file_get_contents('php://input'), true);
                            echo json_encode(GetUserArticles($userID)); //li passo l'api-key i el UserID
                            header('HTTP/1.1 200 OK');
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "user identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "AddComment") {
                    // echo "entra";
                    if ($method == "POST") {
                        if ($identificador != "") {
                            $put = json_decode(file_get_contents('php://input'), true);
                            $missatge = CreateComentari($put);

                            if ($missatge == true) {
                                echo "comment created correctly";
                                header('HTTP/1.1 200 OK');
                            } else {
                                echo "comment creation failed";
                                header('HTTP/1.1 417 EXPECTATION FAILED');
                            }
                        }
                    }
                } else if ($recurs2 == "GetComentsByArticle") {
                    if ($method == "GET") {
                        if ($identificador != "") {
                            echo json_encode(GetComentsByArticle($identificador));
                        } else {
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                            echo "article identifier needed";
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeleteOneComment") {
                    if ($identificador != "") {
                        $missatge = DeleteOneComment($identificador);

                        if ($missatge == true) {
                            echo "Comment deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "comment deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                } else if ($recurs2 == "DeleteArticleComments") {
                    if ($identificador != "") {
                        $missatge = DeleteArticleComments($identificador);

                        if ($missatge == true) {
                            echo "Comments deleted correctly";
                            header('HTTP/1.1 200 OK');
                        } else {
                            echo "comments deletion failed or that paetron was not yours";
                            header('HTTP/1.1 417 EXPECTATION FAILED');
                        }
                    } else {
                        header('HTTP/1.1 405 Method Not Allowed');
                    }
                }
            }
        }
    }
}

$server = new Server;
$server->serve();
