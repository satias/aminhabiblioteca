<?php

include "pt-inputs.php";
include "pt-notif.php";

$pagina_inicial_titulo = "Pagina inicial";
$login_titulo = "Inciar sessao";
$register_titulo = "Criar conta";

//sidebar
$accpages = "Páginas de Conta";
$staffpages = "Páginas de Gestão";
$adminpages = "Páginas de Admin";
//menu links base
$dashboard = "Início";
$livros = "Livros";
$autores = "Autores";
$procurar = "Procurar";
//menu links contas
$perfil = "Perfil";
$requisicoes = "Requisições";
$reservas = "Reservas";
$favoritos = "Favoritos";
$multas = "Multas";
$suporte = "Suporte";
//menu links staff
$procuti = "Procurar Utilizador";
$gerlivr = "Gerir Livros";
$gerauto = "Gerir Autores";
$gertick = "Gerir Tickets";
$listarequisicoes = "Lista de requisições";
$listamultas = "Lista de multas";
//menu links staff
$procont = "Procurar Conta";
$listdel = "Lista para Apagar";
//pagina de perfil
$editarperfil = "Editar Perfil";
$seguranca = "Segurança";
$mudarimagem = "Mudar Imagem";
$apagarconta = "Apagar Conta";
$salvarmudanças = "Salvar Mudanças";
$numeroconta = "Número da Conta";
$prinome = "Primeiro Nome";
$ultnome = "Último Nome";
$numero = "Número de telemóvel";
$morada = "Morada";
$codigopostal = "Código Postal";
$membrodesde = "Membro Desde";
$ultatuali = "Última Atualização";
$statusconta = "Status da Conta";
$bloqueado = "Bloqueado";
$normal = "Normal";
$perfilup = "O perfil foi atualizado com sucesso";
$emailexiste = "Já existe um utilizador com email introduzido";
$numeroinvalido = "O número de telemóvel introduzido é inválido";
$codigopostalinvalido = "O código postal introduzido é inválido";
$naopossvielapagar = "Não é possível apagar a sua conta. Por favor, verifique se possui requisições ou multas ativas e cancele as suas reservas.";
$mensagemapagarsucessocorpo = "A sua conta será apagada em breve, incluindo todos os seus dados pessoais. Se você tentar aceder a conta durante este período, o processo de exclusão será interrompido.";
$mensagemapagarsucessotitulo = "Exclusão de Conta";
$confirmar = "Confirmar";
//dashboard
$bemvindo = "Bem vindo";
$pesqrap = "Pesquisa rápida";
$pesqrapivazio = "Não foram encontrados resultados";
$atualcom = "Atualmente com";
$novoslivros = "Novos Livros";
$livrospop = "Livros Populares";
$topcare = "Categorias populares";
$detalhes = "Detalhes";
$ativo = "Ativo";
$pendente = "Pendente";
//livros
$listalivros = "Lista de Livros";
$proclivro = "Procurar um Livro";
$filtros = "Filtros";
$nomelivro = "Nome do livro";
$nomeautor = "Nome do autor";
$generos = "Gêneros";
$linguagem = "Linguagem";
$editora = "Editora";
$todos = "Todos";
$disponibilidade = "Disponibilidade";
$disponivel = "Disponível";
$Indisponivel = "Indisponível";
$conslocal = "Para consulta local";
//livro detalhes
$detlivro = "Detalhes do Livro";
$datalanc = "Data de lançamento";
$numedit = "Número da edição";
$editora = "Editora";
$numpag = "Número de páginas";
$discbiblio = "Disponível na biblioteca";
$indiscbiblio = "Indisponível na biblioteca";
$disreq = "Disponível para requisição";
$disclocal = "Disponível para consulta local";
$condifisi = "Condição física";
$condinovo = "Novo";
$condibomest = "Bom Estado";
$condiaceitavel = "Aceitável";
$condidesgastado = "Desgastado";
$condimuitodesgas = "Muito Desgastado";
$condimauestado = "Mau Estado";
$codinter = "Código interno";
$descricao = "Descrição";
$oautor = "O Autor";
$requisitar = "Requisitar";
$nenhumgereno = "Nenhum gênero associado a este livro.";
$nenhumautor = "Nenhum autor associado a este livro.";
$reservar = "Reservar";
//requisicoes
$contabloqueada = "A sua conta está bloqueada.";
$vermultas = "Por favor, verifique se possui multas ativas.";
$contsuporte = "Se estiver com algum problema, por favor, entre em contato com o suporte.";
$operafalhada = "Não é possivel concluir a operação.";
$reqs5 = "Você já atingiu o número limite de requisições e reservas.";
$reqlivrodisop = "Você já atingiu o número limite de requisições.";
$reqsucesso = "A sua requisição foi adicionada com sucesso.";
$levantarlivro = "Tem 2 dias para levantar o livro.";
$reqinfor = "Para mais informações consulte a página das requisições";
$rese2 = "Você já atingiu o número limite de reservas.";
$reserinfor = "Para mais informações consulte a página das reservas e requisições";
$resesucesso = "A sua reserva foi adicionada com sucesso.";
$reslimite = "Este livro já atingiu o limite de reservas.";
//autores
$listaautores = "Lista de Autores";
$procautor = "Procurar um Autor";
$nacio = "Nacionalidade";
//autor detalhes
$detautor = "Detalhes do Autor";
$birth = "Nascimento";
$death = "Falecimento";
$sitepessoal = "Website pessoal";
$paginawiki = "Página wiki";
$nodescricao = "Descrição não disponível";
$trabalhodele = "O trabalho dele";
//perfil segurança
$atualpass = "Password atual";
$novapass = "Nova password";
$novousername = "Novo nome de utilizador";
$confinovapass = "Confirmar a nova password";
$alterarusername = "Alterar username";
$alterarpassword = "Alterar password";
//favoritos e multas
$titulo = "Título";
$autor = "Autor";
$edicao = "Edição";
$requisicao = "Requisição";
$dataemissao = "Data de Emissão";
$datapagamento = "Data de Pagamento";
$valor = "Valor";
$remover = "Remover";
$pagar = "Pagar";
$nota = "Nota";
$favmens1 = "Se o utilizador tiver multas em atraso, não poderá fazer novas requisições.";
$favmens2 = "Aproveite o nosso desconto exclusivo para pagamentos na biblioteca.";
$favmens3 = "O pagamento online não está disponível de momento.";
//404
$mens1_404 = "Oops, esta página não pôde ser encontrada.";
$mens2_404 = "A página que está a procurar pode ter sido removida, o seu nome foi alterado ou está temporariamente indisponível.";
//paginas dos tickets
$tipostickets = "Tipo do ticket";
$escolher = "Escolher uma opção";
$titulotexto = "Texto do título";
$desctexto = "Texto da descrição";
$listatickets = "Lista de tickets";
$numeroticket = "Número do ticket";
$atualizadopor = "Atualizado por";
$tipo = "Tipo";
$estado = "Estado";
$criadoa = "Criado a";
$aberto = "Aberto";
$fechado = "Fechado";
$respofechar = "Responder e/ou Fechar";
$respoapenas = "Responder Apenas";
$respoticket = "Responder";
$respotickettextarea = "Texto da resposta";
$respostade = "Resposta de";
$respostaa = "a";
$ticketresposta = "Ticket respondido com sucesso!";
$ticketrespostavazio = "O campo da resposta está vazio";
$ticketrespostafechado = "Ticket respondido e/ou fechado com sucesso!";
$ticketcriar = "O ticket foi criado com sucesso. Por favor aguarde por uma resposta.";
$ticketcriarcamposvazios = "O utilizador tem de dar um título e uma descrição e escolher um tipo que mais se adequa ao seu problema/dúvida.";
$data = "Data";
$criar = "Criar";
$titulodescricao = "Título e descrição";
//reservas
$irpaglivro = "Ir para a página do livro";
$irpagautor = "Ir para a página do autor";
$numeroqueue = "Número da fila";
$dataesperada = "Data esperada";
$atrasado = "Atrasado";
$cancelar = "Cancelar";
$cancelarreservamens = "A sua reserva foi cancelada!";
//lista requisições
$comecaa = "Começa a";
$acabaa = "Acaba a";
$reqdatalimite = "A data limite foi extendida para";
$datacomeço = "Data de começo";
$datalimite = "Data limite";
$entregaatrasada = "Entrega atrasada";
$sim = "Sim";
$nao = "Não";
$historicocompleto = "Histórico completo";
$ver = "Ver";
$ativas = "ativas";
$pendentes = "pendentes";
$com = "com";
$requisicaomensagem = "Clique numa requisição em baixo para ver mais detalhes";
$datalimitemensagem = "A data limite já foi extendida";
//---- páginas admin ----
//gerir tickets
$user = "Utilizador";
$procnomeemailcodigo = "Pesquisar: Nome / Email / Código";
$ordenardata = "Ordenar data";
$nome = "Nome";
$apagaruser = "Apagar utilizador";
$apenasadmin = "Apenas admin";
//procurar conta
$funcionario = "Funcionário";
$bloquearuser = "Bloquear conta";
$desbloquearuser = "Desbloquear conta";
$estado_conta_del = "Conta em processo de exclusão";
$reserva = "reserva";
$entregar = "Entregar";
$ativar = "Ativar";
$cancelarrequi = "A requisição foi cancelada";
$utilizadorapagadoperma = "Utilizador apagado com sucesso";
$mensagemapagarutilizadorpedir = "O pedido para apagar o utilizador foi registrado";
$mensagemapagarutilizadorcancelar = "O pedido para apagar o utilizador foi cancelado";
$cancelarapagar = "Cancelar pedido";
//gerir autores
$adicionarautor = "Adicionar autor";
$editar = "Editar";
//detalhes autor
$informacao = "Informação";
$adicionarimagem = "Adicionar imagem";
$datanasc = "Data de Nascimento";
$datamorte = "Data de Falicimento";
$sitepessoallink = "Link do Website Pessoal";
$paginawikilink = "Link da Wiki";
$facebooklink = "Link do Facebook";
$twitterlink = "Link do Twitter";
$instagramlink = "Link do Instagram";
$redditlink = "Link do Reddit";
$tiktoklink = "Link do TikTok";
$descpt = "Descrição em Português";
$desceng = "Descrição em Inglês";
$apagarautorsucesso = "Autor apagado com sucesso";
$adicionarautorsucesso = "Autor adicionado com sucesso";
$adicionarautorcamposvazios = "Os campos da Imagem, Nome, Data de Nascimento e Nacionalidade têm de ser preenchidos.";
$atualizarautorsucesso = "Autor atualizado com sucesso";
//gerir livros
$adicionarlivro = "Adicionar livro";
$apagarlivrosucesso = "Livro apagado com sucesso";
$adicionarlivrosucesso = "Livro adicionado com sucesso";
$adicionarlivrocamposvazios = "Os campos da Capa, Título, Linguagem e Código interno";
$atualizarlivrosucesso = "Livro atualizado com sucesso";
$codinterisbn = "O código interno e o ISBN tem que ser únicos";
$capa = "Capa";
$frontal = "Frontal";
$traseira = "Traseira";
$genero = "Gênero";
$apagarimpossivel = "Não é possivel apagar este livro";
//lista de requisições
$listarequisicoesativas = "Lista de requisições ativas";
$listarequisicoespendentes = "Lista de requisições pendentes";
//lista de multas
$multa = "Multa"; 