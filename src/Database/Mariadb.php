<?php
namespace App\Database;

class Mariadb {
    private string $host = "localhost"; // endereço do servidor MariaDB
    private string $dbname = "my_tarefas"; // nome do banco de dados
    private string $username = "root"; // usuário do banco de dados
    private string $password = "123456"; // senha do banco de dados
    private ?\PDO $connection =  NULL; // conexão PDO com o banco

    public function __construct() {
        try {
            // Cria uma nova conexão PDO com o banco de dados MariaDB
            $this->connection = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // conexão persistente
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // modo de busca padrão
                    \PDO::ATTR_EMULATE_PREPARES => false // desativa emulação de prepared statements
                ]
            );
        } catch (\PDOException $e) {
            // Lança uma exceção se a conexão falhar
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getConnection(): \PDO {
        // Retorna a conexão PDO
        return $this->connection;
    }
}

?>