<?php
class coneccao
{
   // Propriedade pública para armazenar a conexão ao base de dados
   public $condb;
   // Propriedades privadas para as configurações de conexão
   private $host;
   private $user;
   private $pass;
   private $db;

   // Construtor da classe - define as configurações de conexão ao base de dados
   public function __construct()
   {
      // Definir o nome do servidor (normalmente 'localhost')
      $this->host = "localhost";
      // Definir o nome de utilizador para aceder ao MySQL
      $this->user = "root";
      // Definir a palavra-passe para o utilizador do MySQL
      $this->pass =  "";
      // Definir o nome da base de dados à qual se vai conectar
      $this->db = "aminhabiblioteca";
   }

   // Método para abrir a conexão com o base de dados MySQL
   public function open_db()
   {
      // Criar uma nova instância de mysqli para estabelecer a conexão com o base de dados
      $this->condb = new mysqli($this->host, $this->user, $this->pass, $this->db);
      
      // Verificar se ocorreu algum erro ao tentar conectar
      if ($this->condb->connect_error) {
         // Se houver um erro, exibir uma mensagem e terminar o script
         die("Erro na conexão: " . $this->condb->connect_error);
      }
   }
   // Método para fechar a conexão com o base de dados
   public function close_db()
   {
      // Verificar se a conexão foi estabelecida antes de tentar fechá-la
      if ($this->condb) {
         // Fechar a conexão ao base de dados
         $this->condb->close();
      }
   }
}
?>
