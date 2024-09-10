<?php
// Caminho completo para a pasta de destino (exemplo)
$uploadDir = dirname(dirname(dirname(__FILE__))) . '/libs/img/img-perfil/';

// Verifica se o arquivo foi enviado com sucesso
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Gera um nome único para o arquivo
    $fileName = uniqid() . '_' . basename($_FILES['file']['name']);

    // Move o arquivo para a pasta de destino
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
        // Retorna o caminho relativo do arquivo no servidor
        echo 'libs/img/img-perfil/' . $fileName;
    } else {
        echo ''; // Retorna uma string vazia se ocorrer um erro ao mover o arquivo
    }
} else {
    echo ''; // Retorna uma string vazia se ocorrer um erro no envio do arquivo
}
?>
