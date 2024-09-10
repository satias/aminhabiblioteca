$(document).ready(function () {
  // Seleciona o texto do link com o id 'identidade-site'
  var linkText = $("#identidade-site").text();
  // Altera o título do site para o texto do link
  $("title").text(linkText + " - A Minha Biblioteca");

  $("span.material-symbols-rounded").addClass("notranslate");

  $(".setCookieBtn").click(function () {
    var setlang = $(this).attr("name"); // Obtém o nome do idioma a partir do botão clicado
    $.ajax({
      type: "GET",
      url: "setlanguage.php", // Faz uma requisição ao ficheiro setlanguage.php
      data: {
        setlang: setlang, // Passa o idioma selecionado como parâmetro
      },
      success: function (response) {
        // Se a requisição for bem-sucedida, recarrega a página
        location.reload();
      },
    });
  });
  
  function toggleModal(modalToShow, iconAddToShow, iconRemoveToShow) {
    // Referências aos elementos
    var modals = ["#modal-accpages", "#modal-staffpages", "#modal-adminpages"];
    var iconAdds = [
      "#iconadd-acc-show",
      "#iconadd-staff-show",
      "#iconadd-admin-show",
    ];
    var iconRemoves = [
      "#iconremove-acc-show",
      "#iconremove-staff-show",
      "#iconremove-admin-show",
    ];

    // Fechar todos os modais e ajustar ícones
    modals.forEach(function (modal) {
      if ($(modal).length) {
        $(modal).removeClass("sidebar-modal-open");
      }
    });

    iconAdds.forEach(function (icon) {
      if ($(icon).length) {
        $(icon).removeClass("iconhidden").addClass("iconvisible");
      }
    });

    iconRemoves.forEach(function (icon) {
      if ($(icon).length) {
        $(icon).removeClass("iconvisible").addClass("iconhidden");
      }
    });

    // Abrir/fechar modal atual e ajustar ícones
    if ($(modalToShow).length) {
      $(modalToShow).toggleClass("sidebar-modal-open");
    }
    if ($(iconAddToShow).length) {
      $(iconAddToShow).toggleClass("iconhidden iconvisible");
    }
    if ($(iconRemoveToShow).length) {
      $(iconRemoveToShow).toggleClass("iconhidden iconvisible");
    }
  }

  $("#accpages-show").click(function () {
    toggleModal("#modal-accpages", "#iconadd-acc-show", "#iconremove-acc-show");
  });

  $("#staffpages-show").click(function () {
    toggleModal(
      "#modal-staffpages",
      "#iconadd-staff-show",
      "#iconremove-staff-show"
    );
  });

  $("#adminpages-show").click(function () {
    toggleModal(
      "#modal-adminpages",
      "#iconadd-admin-show",
      "#iconremove-admin-show"
    );
  });
  $("#book-cover-btn").click(function () {
    $("#book-fcover").toggleClass("cover-hidden");
    $("#book-bcover").toggleClass("cover-hidden");
    $("#book-cover-btn").toggleClass("flip");
  });
  $("#book-cover-btn-copy").click(function () {
    $("#book-fcover-copy").toggleClass("cover-hidden");
    $("#book-bcover-copy").toggleClass("cover-hidden");
    $("#book-cover-btn-copy").toggleClass("flip");
  });
  $(".autor-livro-list").slick({
    dots: false,
    infinite: true,
    slidesToShow: 9,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4000,
    accessibility: false,
    arrows: false,
  });
  $(document).on("click", ".profile-link-inactive", function () {
    // Remove 'profile-link-active' de todos os botões e adiciona 'profile-link-inactive'
    $(".profile-link-active")
      .removeClass("profile-link-active")
      .addClass("profile-link-inactive");

    // Adiciona 'profile-link-active' ao botão clicado
    $(this)
      .removeClass("profile-link-inactive")
      .addClass("profile-link-active");
    $("#section-img-perfil").toggleClass("d-none");
    $("#section-username-atualizar").toggleClass("d-none");
    $("#section-infornacao").toggleClass("d-none");
    $("#section-password-atualizar").toggleClass("d-none");
    $("#perfil_salvar").toggleClass("d-none");
  });
  $("#choose-file-btn").on("change", function () {
    var fileInput = this;
    var file = fileInput.files[0];

    // Verifica se um arquivo foi selecionado
    if (file) {
      var formData = new FormData();
      formData.append("file", file);

      // Envia o arquivo para o servidor usando AJAX
      $.ajax({
        url: "views/post_views/img_perfil.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          // Verifica se a resposta não está vazia
          response = response.trim();
          if (response) {
            // Atualiza o background-image após o upload bem-sucedido
            var img = response;
            $("#imagem-perfil").css("background-image", "url('" + img + "')");
            //alert(response);
          } else {
            console.error("Erro ao carregar a imagem");
          }
        },
        error: function () {
          console.error("Erro na requisição AJAX");
        },
      });
    }
  });
  $("#perfil_salvar").click(function () {
    var prinome = $("#perfil-prinome").val();
    var ultnome = $("#perfil-ultnome").val();
    var email = $("#perfil-email").val();
    var oldemail = $("#perfil-email").attr("name");
    var numero = $("#perfil-numero").val();
    var morada = $("#perfil-morada").val();
    var codigopostal = $("#perfil-codigopostal").val();

    var imagem_fundo = $("#imagem-perfil").css("background-image");
    var caminho = imagem_fundo.match(/^url\(["']?(.+?)["']?\)$/);

    var photo_url = caminho[1]; // Obtém o caminho da imagem
    //alert('Caminho da Imagem de Fundo: ' + photo_url);
    $.ajax({
      type: "POST",
      url: "views/post_views/post-atualizar-perfil.php",
      data: {
        prinome: prinome,
        ultnome: ultnome,
        email: email,
        oldemail: oldemail,
        numero: numero,
        morada: morada,
        codigopostal: codigopostal,
        photo_url: photo_url,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#btn-alterar-username").click(function () {
    var password = $("#atualizar-username-pass").val();
    var username = $("#atualizar-username").val();

    $.ajax({
      type: "POST",
      url: "views/post_views/post-atualizar-perfil-username.php",
      data: {
        password: password,
        username: username,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#btn-alterar-password").click(function () {
    var password = $("#atualizar-pass-atual").val();
    var passnova = $("#atualizar-pass-nova").val();
    var passconfinova = $("#atualizar-pass-confinova").val();

    $.ajax({
      type: "POST",
      url: "views/post_views/post-atualizar-perfil-password.php",
      data: {
        password: password,
        passnova: passnova,
        passconfinova: passconfinova,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#perfil-codigopostal").on("input", function () {
    var value = $(this).val().replace(/\D/g, ""); // Remove caracteres não numéricos
    if (value.length > 4) {
      value = value.slice(0, 4) + "-" + value.slice(4, 7); // Adiciona o hífen
    }
    $(this).val(value); // Atualiza o valor do campo
  });

  // Impedir a entrada de letras e outros caracteres não numéricos
  $("#perfil-codigopostal").on("keypress", function (event) {
    var charCode = event.which ? event.which : event.keyCode;
    if (charCode != 8 && charCode != 45 && (charCode < 48 || charCode > 57)) {
      event.preventDefault();
    }
  });
  $("#perfil-numero").on("keypress", function (event) {
    var charCode = event.which ? event.which : event.keyCode;
    if (charCode < 48 || charCode > 57) {
      event.preventDefault();
    }
  });
  $("#botao_apagar").click(function () {
    var password = $("#apagar-password").val();
    //alert('Caminho da Imagem de Fundo: ' + photo_url);
    $.ajax({
      type: "POST",
      url: "views/post_views/post_pedir_apagar_user.php",
      data: {
        password: password,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#apagarmodal").html(response.message);
        } else {
          $("#apagarmensagens").html(response.message);
        }
      },
    });
  });

  $("#pesq-rapida").on("focus input", function () {
    var nome = $(this).val();
    if (nome.length > 0) {
      $("#pesq-rapida-tab").removeClass("d-none");
      $("#col-pesquisa").addClass("overflow-visible");

      $.ajax({
        type: "POST",
        url: "views/post_views/post_pesq_rapida.php",
        data: { nome: nome },
        dataType: "html",
        success: function (response) {
          $("#pesq-rapida-tab").html(response);
        },
      });
    } else {
      $("#pesq-rapida-tab").html(""); // Limpar os resultados se o campo de entrada estiver vazio
      $("#pesq-rapida-tab").addClass("d-none");
      $("#col-pesquisa").removeClass("overflow-visible");
    }
  });

  // Esconder a div quando o campo de entrada perde o foco
  $("#pesq-rapida").on("blur", function () {
    $("#pesq-rapida-tab").addClass("d-none");
    $("#col-pesquisa").removeClass("overflow-visible");
  });

  $(document).on("mousedown", "tr[data-href]", function () {
    window.location.href = $(this).data("href");
  });
  $(".pesquisa-check").on("click", function () {
    // Desmarcar todos os checkboxes
    $(".pesquisa-check").not(this).prop("checked", false);
  });
  $("#reload-btn").on("click", function () {
    location.reload();
  });
  $(".pesquisa-form").on("input change", function () {
    var bookname = $("#pesquisa-booknome").val();
    var authorname = $("#pesquisa-authornome").val();
    var genero = $("#pesquisa-genero").val();
    var linguagem = $("#pesquisa-linguagem").val();
    var editora = $("#pesquisa-editora").val();
    var dispo = $("#pesquisa-dispo").is(":checked") ? 1 : 0;
    var indispo = $("#pesquisa-indispo").is(":checked") ? 1 : 0;
    var localcons = $("#pesquisa-localcons").is(":checked") ? 1 : 0;
    var url = window.location.href;

    $.ajax({
      type: "POST",
      url: "views/post_views/post_pesquisa_livros.php",
      data: {
        bookname: bookname,
        authorname: authorname,
        genero: genero,
        linguagem: linguagem,
        editora: editora,
        dispo: dispo,
        indispo: indispo,
        localcons: localcons,
        url: url,
      },
      dataType: "html",
      success: function (response) {
        $(".grid-list").html(response);
      },
    });
  });
  $(".pesquisa-author-form").on("input change", function () {
    var authorname = $("#pesquisa-authorname").val();
    var nacionalidade = $("#pesquisa-nacionalidade").val();
    var url = window.location.href;

    $.ajax({
      type: "POST",
      url: "views/post_views/post_pesquisa_autor.php",
      data: {
        authorname: authorname,
        nacionalidade: nacionalidade,
        url: url,
      },
      dataType: "html",
      success: function (response) {
        $(".grid-list").html(response);
        console.log(url);
      },
    });
  });
  $(".remover-favorito").on("click", function () {
    // Captura o id do botão clicado
    var bookid = $(this).attr("id");

    $.ajax({
      type: "POST",
      url: "views/post_views/post_remover_favorito_pag.php",
      data: { bookid: bookid },
      dataType: "json", // Mudando para JSON para facilitar o processamento
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se a remoção foi bem-sucedida
        } else {
          $("#alert-container").html(response.data);
        }
      },
      error: function (xhr, status, error) {
        console.error("Erro na requisição AJAX:", status, error);
      },
    });
  });
  $("#ticket-criar-titulo").on("keypress", function (event) {
    if (event.which === 13) {
      // 13 é o código da tecla Enter
      event.preventDefault();
    }
  });
  $("#ticket-criar-titulo").on("input", function () {
    $(this).val($(this).val().replace(/\n/g, " "));
  });
  $("#ticket-criar-botao").click(function () {
    var tipo = $("#ticket-criar-tipo").val();
    var titulo = $("#ticket-criar-titulo").val();
    var descricao = $("#ticket-criar-descricao").val();
    $.ajax({
      type: "POST",
      url: "views/post_views/post_inserir_ticket.php",
      data: {
        tipo: tipo,
        titulo: titulo,
        descricao: descricao,
      },
      dataType: "html",
      success: function (response) {
        if (response) {
          $("#alert-container").html(response);
        }
      },
    });
  });
  $("#invertRows").click(function () {
    var $rows = $("#myTable tbody tr").get().reverse();
    $.each($rows, function (index, row) {
      $("#myTable tbody").append(row);
    });
    $(this).find(".material").toggleClass("opacidade-60");
  });
  $(".btn-remover-reserva").click(function () {
    var reserva_id = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: "views/post_views/post_remover_reserva.php",
      data: {
        reserva_id: reserva_id,
      },
      dataType: "html",
      success: function (response) {
        if (response) {
          $("#alert-container").html(response);
        } else {
          console.log("Empty response");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX error: " + textStatus + " : " + errorThrown);
      },
    });
  });
  $(".btn-requisicao-detalhes").click(function () {
    var req_id = $(this).attr("id");
    var req_caixa_id = "req-caixa-" + req_id;
    //alert(req_caixa_id);
    $('div[name="req-caixa"]').removeClass("border-1-primary");
    $('div[name="req-caixa"]').find("#req-caixa-seta").remove();
    $("#" + req_caixa_id).addClass("border-1-primary");
    $("#" + req_caixa_id).append(`
      <span class="material-symbols-outlined icon-25 position-absolute seta-cima-direita" id="req-caixa-seta">
          arrow_upward
      </span>`);
    $.ajax({
      type: "POST",
      url: "views/post_views/mostrar_req_detalhes.php",
      data: {
        req_id: req_id,
      },
      dataType: "html",
      success: function (response) {
        $("#req-detalhes").html(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX error: " + textStatus + " : " + errorThrown);
      },
    });
  });
  $("#procurarconta-input").on("focus input", function () {
    var nome = $(this).val().trim(); // Garantir que espaços em branco são removidos
    // console.log(
    //   "Nome atual: '" + nome + "' (Comprimento: " + nome.length + ")"
    // );

    if (nome.length > 0) {
      //console.log("Entrou no IF");
      $("#procurarconta-input-tab").removeClass("d-none");
      $("#col-pesquisa").addClass("overflow-visible");

      $.ajax({
        type: "POST",
        url: "views/post_views/post_pesq_conta.php",
        data: { nome: nome },
        dataType: "html",
        success: function (response) {
          $("#procurarconta-input-tab").html(response);
        },
      });
    } else {
      //console.log("Entrou no ELSE");
      $("#procurarconta-input-tab").html(""); // Limpar os resultados se o campo de entrada estiver vazio
      $("#procurarconta-input-tab").addClass("d-none");
      $("#col-pesquisa").removeClass("overflow-visible");
    }
  });
  // Esconder a div quando o campo de entrada perde o foco
  $("#procurarconta-input").on("focusout", function (event) {
    // Usa setTimeout para esperar o clique ser processado
    setTimeout(function () {
      // Verifica se o elemento focado não é o procurarconta-input-tab
      if (!$("#procurarconta-input-tab").is(":hover")) {
        $("#procurarconta-input-tab").addClass("d-none");
        $("#col-pesquisa").removeClass("overflow-visible");
      }
    }, 100);
  });

  $("#procurarconta-input-tab").on("click", function () {
    // Impede que o blur do input esconda a div se ela foi clicada
    $("#procurarconta-input").off("focusout");
  });
  $(document).on("click", "td[data-href]", function (event) {
    if (event.which === 1) {
      // Verifica se foi o botão esquerdo do mouse
      window.location.href = $(this).data("href");
    }
  });
  $("#procurar-botao-editar-utilizador").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();
    var prinome = $("#procurar-editar-utilizador-prinome").val();
    var ultnome = $("#procurar-editar-utilizador-ultnome").val();
    var email = $("#procurar-editar-utilizador-email").val();
    var numero = $("#procurar-editar-utilizador-numero").val();
    var morada = $("#procurar-editar-utilizador-morada").val();
    var codigopostal = $("#procurar-editar-utilizador-codigopostal").val();

    //console.log(numero);
    $.ajax({
      type: "POST",
      url: "views/post_views/post_editar_perfil_utilizador.php",
      data: {
        user_id: user_id,
        prinome: prinome,
        ultnome: ultnome,
        email: email,
        numero: numero,
        morada: morada,
        codigopostal: codigopostal,
      },
      dataType: "html",
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#procurar-editar-utilizador-codigopostal").on("input", function () {
    var value = $(this).val().replace(/\D/g, ""); // Remove caracteres não numéricos
    if (value.length > 4) {
      value = value.slice(0, 4) + "-" + value.slice(4, 7); // Adiciona o hífen
    }
    $(this).val(value); // Atualiza o valor do campo
  });
  // Impedir a entrada de letras e outros caracteres não numéricos
  $("#procurar-editar-utilizador-codigopostal").on(
    "keypress",
    function (event) {
      var charCode = event.which ? event.which : event.keyCode;
      if (charCode != 8 && charCode != 45 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
      }
    }
  );
  $("#procurar-editar-utilizador-numero").on("keypress", function (event) {
    var charCode = event.which ? event.which : event.keyCode;
    if (charCode < 48 || charCode > 57) {
      event.preventDefault();
    }
  });
  $("#procurar-botao-status-utilizador").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();
    var status = $(this).text();

    console.log(user_id + " " + status);
    $.ajax({
      type: "POST",
      url: "views/post_views/post_bloquear_desbloquear_utilizador.php",
      data: {
        user_id: user_id,
        status: status,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se for sucesso
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $(".procurar-botao-pagar-multa").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();
    var multa_id = $(this).attr("name");
    var totalmultas = $(".procurar-botao-pagar-multa").length;

    $.ajax({
      type: "POST",
      url: "views/post_views/post_pagar_multa.php",
      data: {
        user_id: user_id,
        multa_id: multa_id,
        totalmultas: totalmultas,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se for sucesso
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $("#procurar-botao-status-utilizador").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();
    var status = $(this).text();

    console.log(user_id + " " + status);
    $.ajax({
      type: "POST",
      url: "views/post_views/post_bloquear_desbloquear_utilizador.php",
      data: {
        user_id: user_id,
        status: status,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se for sucesso
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $(".procurar-botao-entregar-livro").click(function () {
    var requisicao_id = $(this).attr("name");

    $.ajax({
      type: "POST",
      url: "views/post_views/post_entregar_livro.php",
      data: {
        requisicao_id: requisicao_id,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se for sucesso
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $(".procurar-botao-ativar-requisicao").click(function () {
    var requisicao_id = $(this).attr("name");

    $.ajax({
      type: "POST",
      url: "views/post_views/post_ativar_requisicao.php",
      data: {
        requisicao_id: requisicao_id,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          location.reload(); // Recarrega a página se for sucesso
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $(".pesquisa-ticket-form").on("input change", function () {
    var texto = $("#gerir-ticket-pesquisa-texto").val();
    var tipo = $("#gerir-ticket-pesquisa-tipo").val();
    console.log(texto + " " + tipo);
    $.ajax({
      type: "POST",
      url: "views/post_views/post_pesquisa_tickets.php",
      data: {
        texto: texto,
        tipo: tipo,
      },
      dataType: "html",
      success: function (response) {
        $("#pesquisa-conteudo").html(response);
      },
    });
  });
  $(".gerir-ticket-apagar-utilizador").click(function () {
    var user_id = $(this).attr("name");

    $.ajax({
      type: "POST",
      url: "views/post_views/post_apagar_utilizador_perma.php",
      data: {
        user_id: user_id,
      },
      dataType: "json", // A resposta agora é JSON
      success: function (response) {
        if (response.success) {
          $("#alert-container").html(`
            <div class="alert alert-warning alert-dismissible" role="alert">
                ${response.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onClick="window.location.reload();"></button>
            </div>
        `);
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
        }
      },
    });
  });
  $("#procurar-botao-apagar-utilizador").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();

    $.ajax({
      type: "POST",
      url: "views/post_views/post_pedir_apagar_user_procconta.php",
      data: {
        user_id: user_id,
      },
      dataType: "html", // A resposta agora é JSON
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#procurar-botao-cancelar-apagar-utilizador").click(function () {
    var user_id = $("#procurar-editar-utilizador-id").val();

    $.ajax({
      type: "POST",
      url: "views/post_views/post_cancelar_apagar_procconta.php",
      data: {
        user_id: user_id,
      },
      dataType: "html", // A resposta agora é JSON
      success: function (response) {
        $("#alert-container").html(response);
      },
    });
  });
  $("#choose-file-btn-autor").on("change", function () {
    var fileInput = this;
    var file = fileInput.files[0];

    // Verifica se um arquivo foi selecionado
    if (file) {
      var formData = new FormData();
      formData.append("file", file);

      // Envia o arquivo para o servidor usando AJAX
      $.ajax({
        url: "views/post_views/img_autor.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          // Verifica se a resposta não está vazia
          response = response.trim();
          if (response) {
            // Atualiza o background-image após o upload bem-sucedido
            var img = response;
            $("#imagem-perfil").css("background-image", "url('" + img + "')");
            //alert(response);
          } else {
            console.error("Erro ao carregar a imagem");
          }
        },
        error: function () {
          console.error("Erro na requisição AJAX");
        },
      });
    }
  });
  $("#gerir-autor-botao-editar").click(function () {
    var autor_id = $("#gerir-autor-id").val();
    var prinome = $("#gerir-autor-prinome").val();
    var ultnome = $("#gerir-autor-ultnome").val();
    var datanasc = $("#gerir-autor-datanasc").val();
    var datamorte = $("#gerir-autor-datamorte").val();
    var nacionalidade = $("#gerir-autor-nacionalidade").val();
    var websitepessoal = $("#gerir-autor-websitepessoal").val();
    var wiki = $("#gerir-autor-wiki").val();
    var facebook = $("#gerir-autor-facebook").val();
    var twitter = $("#gerir-autor-twitter").val();
    var instagram = $("#gerir-autor-instagram").val();
    var reddit = $("#gerir-autor-reddit").val();
    var tiktok = $("#gerir-autor-tiktok").val();
    var desc_pt = $("#gerir-autor-desc_pt").val();
    var desc_eng = $("#gerir-autor-desc_eng").val();

    var imagem_fundo = $("#imagem-perfil").css("background-image");
    var caminho = imagem_fundo.match(/^url\(["']?(.+?)["']?\)$/);
    if (caminho && caminho[1]) {
      var url = caminho[1];
      var urlObj = new URL(url);
      var photo_url = urlObj.pathname.split("/").pop();
      //alert("Nome do Arquivo da Imagem de Fundo: " + photo_url);
    }
    $.ajax({
      type: "POST",
      url: "views/post_views/post_editar_autor.php",
      data: {
        autor_id: autor_id,
        prinome: prinome,
        ultnome: ultnome,
        datanasc: datanasc,
        datamorte: datamorte,
        nacionalidade: nacionalidade,
        websitepessoal: websitepessoal,
        wiki: wiki,
        facebook: facebook,
        twitter: twitter,
        instagram: instagram,
        reddit: reddit,
        tiktok: tiktok,
        desc_pt: desc_pt,
        desc_eng: desc_eng,
        photo_url: photo_url,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#alert-container").html(`
            <div class="alert alert-success alert-dismissible" role="alert">
                ${response.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
            <div class="alert alert-warning alert-dismissible" role="alert">
                ${response.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        }
      },
    });
  });
  $(".verificar_numero_input").on("keypress", function (event) {
    var charCode = event.which ? event.which : event.keyCode;
    // Permitir apenas números (0-9)
    if (charCode < 48 || charCode > 57) {
      event.preventDefault();
    }
  });
  $("#gerir-livro-botao-editar").click(function () {
    var book_id = $("#gerir-livro-id").val();
    var titulo = $("#gerir-livro-titulo").val();
    var linguagem = $("#gerir-livro-linguagem").val();
    var codinter = $("#gerir-livro-codinter").val();
    var editora = $("#gerir-livro-editora").val();
    var datalanc = $("#gerir-livro-datalanc").val();
    var isbn = $("#gerir-livro-isbn").val();
    var numedit = $("#gerir-livro-numedit").val();
    var numpag = $("#gerir-livro-numpag").val();
    var condicao = $("#gerir-livro-condicao").val();
    var discbiblio = $("#gerir-livro-discbiblio").is(":checked") ? 1 : 0;
    var disreq = $("#gerir-livro-disreq").is(":checked") ? 1 : 0;
    var desc_pt = $("#gerir-livro-desc_pt").val();
    var desc_eng = $("#gerir-livro-desc_eng").val();

    var imagem_capa = $("#imagem-capa").css("background-image");
    var imagem_contracapa = $("#imagem-contracapa").css("background-image");
    var caminho_capa = imagem_capa.match(/^url\(["']?(.+?)["']?\)$/);
    var caminho_contracapa = imagem_contracapa.match(
      /^url\(["']?(.+?)["']?\)$/
    );

    var photo_url_capa = "";
    var photo_url_contracapa = "";

    if (caminho_capa && caminho_capa[1]) {
      var url = caminho_capa[1];
      var urlObj = new URL(url);
      photo_url_capa = urlObj.pathname.split("/").pop();
    }
    if (caminho_contracapa && caminho_contracapa[1]) {
      var url = caminho_contracapa[1];
      var urlObj = new URL(url);
      photo_url_contracapa = urlObj.pathname.split("/").pop();
    }

    // Coletar IDs das divs dentro de #lista-generos e transformar em JSON
    var idsArray = [];
    $(".book-genres-box").each(function () {
      var id = parseInt($(this).attr("id"), 10); // Pega o valor do atributo id e converte para número
      if (!isNaN(id)) {
        idsArray.push(id); // Adiciona o ID ao array, se for um número válido
      }
    });

    var autor_id = $("#gerir-livro-autor").length
      ? $("#gerir-livro-autor").val()
      : null;

    //console.log(idsArray); // Verifique o conteúdo do array antes de converter
    var idsJson = JSON.stringify(idsArray);
    //console.log(idsJson); // Verifique o JSON resultante
    $.ajax({
      type: "POST",
      url: "views/post_views/post_editar_livro.php",
      data: {
        book_id: book_id,
        titulo: titulo,
        linguagem: linguagem,
        codinter: codinter,
        editora: editora,
        datalanc: datalanc,
        isbn: isbn,
        numedit: numedit,
        numpag: numpag,
        condicao: condicao,
        discbiblio: discbiblio,
        disreq: disreq,
        desc_pt: desc_pt,
        desc_eng: desc_eng,
        photo_url_capa: photo_url_capa,
        photo_url_contracapa: photo_url_contracapa,
        generos: idsJson,
        autor_id: autor_id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#alert-container").html(`
              <div class="alert alert-success alert-dismissible" role="alert">
                  ${response.message}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `);
        } else {
          // Se não for sucesso, podes mostrar uma mensagem de erro
          $("#alert-container").html(`
              <div class="alert alert-warning alert-dismissible" role="alert">
                  ${response.message}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `);
        }
      },
    });
  });
});
