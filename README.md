O projeto A Minha Biblioteca pode ser encontrado no GitHub no seguinte link:  por link. A base de dados necessária também está disponível numa pasta separada chamada base de dados dentro do repositório.
Passos para Instalação
Fazer Download do Projeto:
1.	Aceda ao repositório do GitHub e faça o download dos ficheiros.
2.	Após o download, extraia a pasta aminhabiblioteca para a pasta htdocs do XAMPP (ou equivalente em outros softwares):
Configuração da Base de Dados:
1.	Dentro da pasta do projeto no GitHub, localize a pasta base de dados.
2.	Importe o ficheiro SQL da base de dados para o MySQL via phpMyAdmin (ou linha de comando MySQL):
o	Abra o phpMyAdmin (geralmente em http://localhost/phpmyadmin).
o	Crie uma base de dados chamada aminhabiblioteca.
o	Importe o ficheiro SQL aminhabiblioteca.sql localizado na pasta base de dados.
Configuração do Ficheiro de Conexão com a Base de Dados:
1.	Dentro da pasta aminhabiblioteca, localize o ficheiro “connection.php” dentro da pasta “modelo”.
2.	Atualize as credenciais da base de dados dentro do construtor com os valores adequados.
 
Configuração de Caminhos no Projeto
Se o projeto A Minha Biblioteca não for alojado diretamente na pasta htdocs (no caso de usar XAMPP) ou em qualquer outro diretório padrão de uma ferramenta de servidor local, será necessário ajustar alguns caminhos de diretório no código. Caso o projeto tenha sido colocado diretamente na pasta htdocs, esta etapa pode ser ignorada.
Existem três ficheiros onde será necessário alterar o caminho para corresponder à localização do projeto no servidor.
1.	.htaccess
No ficheiro .htaccess, a diretiva RewriteBase deve ser ajustada para refletir o caminho correto do projeto:
 
•	Linha a ser alterada: RewriteBase /aminhabiblioteca/
•	Nova linha: Ajuste o caminho para o diretório onde o projeto está instalado. Exemplo:
o	Se o projeto estiver em http://localhost/meuprojeto/aminhabiblioteca/, a linha deve ser:
RewriteBase /meuprojeto/aminhabiblioteca/
2.	route.php
No ficheiro route.php, o caminho base utilizado para identificar as rotas também precisa de ser atualizado:
 
•	Linha a ser alterada: $base_path = "/aminhabiblioteca";
•	Nova linha: Ajuste para o caminho completo. Exemplo:
$base_path = "/meuprojeto/aminhabiblioteca";
3.	funcoes.php
No ficheiro funcoes.php, o caminho base para as funções do projeto também deve ser modificado:
 
•	Linha a ser alterada: $diretorio_base = "/aminhabiblioteca/";
•	Nova linha: Ajuste o caminho conforme necessário. Exemplo:
$diretorio_base = "/meuprojeto/aminhabiblioteca/";
Resumo
Caso o projeto tenha sido colocado numa pasta diferente de htdocs, será necessário ajustar os caminhos nos ficheiros:
•	.htaccess na linha RewriteBase
•	route.php na linha $base_path
•	funcoes.php na linha $diretorio_base
Esses ajustes garantem que o roteamento e outras funcionalidades do projeto funcionem corretamente no novo caminho do servidor local.
