<?php 
namespace App\Models;

class Usuario
{
  
  private \PDO $connection;

    public function __construct(\PDO $connection)
  {
    $this->connection = $connection;
  }

  public ?int $id = null;
  public string $nome = "";
  public string $login = "";
  public string $senha = "";
  public string $email = "";
  public string $foto_path = "";
  // criar a propriedade de conexão igual a classe tarefas
  // criar metodo construtor igual a tarefas
  // criar os metodos create, finById, update e delete para gerenciar usuarios 

    public function create(): bool
  {
    $sql = "INSERT INTO usuario (id, nome, login, senha, email, foto_path) VALUES (:id, :nome, :login, :senha, :email, :foto_path)";
    $stmt = $this->connection->prepare($sql);
    return $stmt->execute([
      ':id' => $this->id,
      ':nome' => $this-> nome,
      ':login' => $this->login,
      ':senha' => password_hash($this->senha, PASSWORD_DEFAULT),
      ':email' => $this->email,
      ':foto_path' => $this->foto_path
    ]);
  }

  public function findById(int $id): ?array
  {
    $sql = "SELECT * FROM usuario WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
  
  public function update(): bool
  {
    $sql = "UPDATE usuario SET nome = :nome, login = :login, senha = :senha, foto_path = :foto_path WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    return $stmt->execute([
      ':id' => $this->id,
      ':nome' => $this->nome,
      ':login' => $this->login,
      ':senha' => $this->senha,
      ':email' => $this->email,
      ':foto_path' => $this->foto_path
    ]);
  }
  public function delete(int $id): bool
  {
    $sql = "DELETE FROM usuario WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    return $stmt->execute([':id' => $id]);
  }
} 
?>