<?php
include_once "setlanguage.php";  // Carrega o arquivo de configuração de idioma
include_once "funcoes/funcoes.php"; // Funções globais
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Favicon da aplicação -->
    <link rel="icon" href="<?php echo get_link("") ?>libs/img/logos/svg/logo-color.svg" type="image/x-icon">
    
    <!-- Bibliotecas externas -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
    <!-- Fontes e estilos -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="<?php echo get_link("") ?>libs/sass/styles.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- Função AJAX para atualização de informações -->
    <script>
        (function() {
            $.ajax({
                url: "views/post_views/post_atualizar_informacoes.php",
                type: "POST",
                contentType: false,
                processData: false,
                success: function() {
                    //console.log("Requisição AJAX bem-sucedida");
                },
                error: function() {
                    //console.error("Erro na requisição AJAX");
                },
            });
        })();
    </script>
</head>
<body>
