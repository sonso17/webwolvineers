<?php
/*
        Function: classBdD()

            Classe que s'encarrega de fer la connexió amb la base de dades

        Returns:

            Retorna l'error si algo ha fallat o la connexió si s'ha connectat correctament
 
    */
class BdD
{
	public $db_host;
	public $db_user;
	public $db_password;
	public $db_name;

	public function __construct()
	{
		$this->db_host = "localhost";
		$this->db_user = "root";
		$this->db_password = "";
		$this->db_name = "wolvineers";
	}

	public function connect()
	{
		$db_connection = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name);

		if (!$db_connection) {
			return "Connection error";
		} else {
			return $db_connection;
		}
	}
}