<?php

include "eng-inputs.php";
include "eng-notif.php";

$pagina_inicial_titulo = "Home";
$login_titulo = "Sign in";
$register_titulo = "Sign up";

//sidebar
$accpages = "Account Pages";
$staffpages = "Management Pages";
$adminpages = "Admin Pages";
//menu links
$dashboard = "Dasboard";
$livros = "Books";
$autores = "Authors";
$procurar = "Search";
//menu links contas
$perfil = "Profile";
$requisicoes = "Requests";
$reservas = "Reserves";
$favoritos = "Bookmarks";
$multas = "Fines";
$suporte = "Support";
//menu links staff
$procuti = "User Search";
$gerlivr = "Book Management";
$gerauto = "Author Management";
$gertick = "Ticket Management";
$listarequisicoes = "List of requests";
$listamultas = "List of fines";
//menu links staff
$procont = "Account Search";
$listdel = "List to Delete";
//pagina de perfil
$editarperfil = "Edit Profile";
$seguranca = "Security";
$mudarimagem = "Change Picture";
$apagarconta = "Delete Account";
$salvarmudanças = "Save Changes";
$numeroconta = "Account Number";
$prinome = "First Name";
$ultnome = "Last Name";
$numero = "Phone number";
$morada = "Address";
$codigopostal = "Postal Code";
$membrodesde = "Member Since";
$ultatuali = "Last Update";
$statusconta = "Account Status";
$bloqueado = "Blocked";
$normal = "Normal";
$perfilup = "O perfil foi atualizado com sucesso";
$emailexiste = "There is already a user with an email entered";
$numeroinvalido = "The cell phone number entered is invalid";
$codigopostalinvalido = "The postal code entered is invalid";
$naopossvielapagar = "It is not possible to delete your account. Please check if you have any active requests or fines and cancel your reservations.";
$mensagemapagarsucessocorpo = "Your account will be deleted shortly, including all your personal data. If you try to access the account during this period, the deletion process will be interrupted.";
$mensagemapagarsucessotitulo = "Account Termination";
$confirmar = "Confirm";
//dashboard
$bemvindo = "Welcome";
$pesqrap = "Quick search";
$pesqrapivazio = "No results found";
$atualcom = "Currently with";
$novoslivros = "New Books";
$livrospop = "Popular Books";
$topcare = "Popular Categories";
$detalhes = "Details";
$ativo = "Active";
$pendente = "Pendent";
//livros
$listalivros = "List of books";
$proclivro = "Search for a book";
$filtros = "Filters";
$nomelivro = "Name of the book";
$nomeautor = "Author's name";
$generos = "Genres";
$linguagem = "Language";
$editora = "Publisher";
$todos = "All";
$disponibilidade = "Availability";
$disponivel = "Available";
$Indisponivel = "Unavailable";
$conslocal = "For local consultation";
//livro detalhes
$detlivro = "Book details";
$datalanc = "Release date";
$numedit = "Edition number";
$editora = "Publisher";
$numpag = "Number of pages";
$discbiblio = "Available in the library";
$indiscbiblio = "Not available in the library";
$disreq = "Available for request";
$disclocal = "Available for local consultation";
$condifisi = "Physical condition";
$condinovo = "New";
$condibomest = "Good condition";
$condiaceitavel = "Acceptable";
$condidesgastado = "Worn out";
$condimuitodesgas = "Very worn";
$condimauestado = "Bad condition";
$codinter = "Internal code";
$descricao = "Description";
$oautor = "The Author";
$requisitar = "Request";
$nenhumgereno = "No genre associated with this book.";
$nenhumautor = "No author associated with this book.";
$reservar = "Reserve";
//requests
$contabloqueada = "Your account is blocked.";
$vermultas = "Please check if you have any active fines.";
$contsuporte = "If you have any problems, please contact support.";
$operafalhada = "It is not possible to complete the operation.";
$reqs5 = "You have already reached the limit number of requests.";
$reqlivrodisop = "You have already reached the limit number of requests and reservations.";
$reqsucesso = "Your request has been added successfully.";
$levantarlivro = "You have 2 days to pick up the book.";
$reqinfor = "For more information see the requisitions page";
$rese2 = "You have already reached the limit number of reservations and requests.";
$reserinfor = "For more information see the reservations page";
$resesucesso = "Your reservation has been added successfully.";
$reslimite = "This book has already reached its reservation limit.";
//autores
$listaautores = "List of Authors";
$procautor = "Search for an Author";
$nacio = "Nationality";
//autor detalhes
$detautor = "Author Details";
$birth = "Birth";
$death = "Death";
$sitepessoal = "Personal website";
$paginawiki = "Wiki page";
$nodescricao = "Description not available";
$trabalhodele = "His work";
//perfil segurança
$atualpass = "Current password";
$novapass = "New password";
$novousername = "New username";
$confinovapass = "Confirm new password";
$alterarusername = "Change username";
$alterarpassword = "Change password";
//favoritos e multas
$titulo = "Title";
$autor = "Author";
$edicao = "Edition";
$requisicao = "Requisition";
$dataemissao = "Issue Date";
$datapagamento = "Payment date";
$valor = "Amount";
$remover = "Remove";
$pagar = "Pay";
$nota = "Note";
$favmens1 = "If the user has any overdue fines, they will not be able to make new requests.";
$favmens2 = "Take advantage of our exclusive discount for payments in the library.";
$favmens3 = "Online payment is not available at the moment.";
//404
$mens1_404 = "Oops, This Page Could Not Be Found.";
$mens2_404 = "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.";
//paginas dos tickets
$tipostickets = "Ticket type";
$escolher = "Choose an option";
$titulotexto = "Title text";
$desctexto = "Description text";
$listatickets = "List of tickets";
$numeroticket = "Ticket number";
$atualizadopor = "Updated by";
$tipo = "Type";
$estado = "Status";
$criadoa = "Created on";
$aberto = "Open";
$fechado = "Closed";
$respofechar = "Reply and/or Close";
$respoapenas = "Reply Only";
$respoticket = "Reply";
$respotickettextarea = "Reply text";
$respostade = "Reply of";
$respostaa = "on";
$ticketresposta = "Ticket successfully replied to!";
$ticketrespostavazio = "The reply field is empty";
$ticketrespostafechado = "Ticket successfully answered and/or closed!";
$ticketcriar = "The ticket was successfully created. Please wait for a reply.";
$ticketcriarcamposvazios = "The user has to give a title and description and choose a type that best suits their problem/question.";
$data = "Date";
$criar = "Create";
$titulodescricao = "Title and description";
//reservas
$irpaglivro = "Go to book page";
$irpagautor = "Go to author page";
$numeroqueue = "Queue number";
$dataesperada = "Expected date";
$atrasado = "Delayed";
$cancelar = "Cancel";
$cancelarreservamens = "Your reservation has been canceled!";
//lista requisições
$comecaa = "Starts on";
$acabaa = "Ends on";
$reqdatalimite = "The deadline has been extended to";
$datacomeço = "Start date";
$datalimite = "Deadline";
$entregaatrasada = "Delayed delivery";
$sim = "Yes";
$nao = "No";
$historicocompleto = "Complete history";
$ver = "See";
$ativas = "active";
$pendentes = "pending";
$com = "with";
$requisicaomensagem = "Click on a request below to see more details";
$datalimitemensagem = "The deadline has already been extended";
//---- páginas admin ----
//gerir tickets
$user = "User";
$procnomeemailcodigo = "Search: Name / Email / Code";
$ordenardata = "Sort date";
$nome = "Name";
$apagaruser = "Delete user";
$apenasadmin ="Admin only";
//procurar conta
$funcionario = "Staff";
$bloquearuser ="Block account";
$desbloquearuser ="Unblock account";
$estado_conta_del = "Account in process of deletion";
$reserva = "reserve";
$entregar = "Deliver";
$ativar = "Activate";
$cancelarrequi = "The request has been canceled";
$utilizadorapagadoperma = "User successfully deleted";
$mensagemapagarutilizadorpedir = "The request to delete the user has been registered";
$mensagemapagarutilizadorcancelar = "The request to delete the user has been canceled";
$cancelarapagar = "Cancel request";
// Gerir autores
$adicionarautor = "Add Author";
$editar = "Edit";
// Detalhes do autor
$informacao = "Information";
$adicionarimagem = "Add Image";
$datanasc = "Date of Birth";
$datamorte = "Date of Death";
$sitepessoallink = "Personal Website Link";
$paginawikilink = "Wiki Link";
$facebooklink = "Facebook Link";
$twitterlink = "Twitter Link";
$instagramlink = "Instagram Link";
$redditlink = "Reddit Link";
$tiktoklink = "TikTok Link";
$descpt = "Description in Portuguese";
$desceng = "Description in English";
$apagarautorsucesso = "Author successfully deleted";
$adicionarautorsucesso = "Author successfully added";
$adicionarautorcamposvazios = "The fields for Image, Name, Date of Birth, and Nationality must be filled in.";
$atualizarautorsucesso = "Author successfully updated";
//gerir livros
$adicionarlivro = "Add book";
$apagarlivrosucesso = "Book successfully deleted";
$adicionarlivrosucesso = "Book added successfully";
$adicionarlivrocamposvazios = "The Cover, Title, Language and Internal Code fields";
$atualizarlivrosucesso = "Book updated successfully";
$codinterisbn = "The internal code and ISBN must be unique";
$capa = "Cover";
$frontal = "Front";
$traseira = "Back";
$genero = "Genre";
$apagarimpossivel = "It is not possible to delete this book";
//lista de requisições
$listarequisicoesativas = "List of active requisitions";
$listarequisicoespendentes = "List of pending requisitions";
//lista de multas
$multa = "Fine"; 