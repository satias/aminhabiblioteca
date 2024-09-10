<?php
$request = $_SERVER["REQUEST_URI"];

$base_path = "/aminhabiblioteca"; 
$rest = substr($request, strlen($base_path));

switch ($rest) {
    case "":
    case "/":
        require __DIR__ . "/views/home.php";
        break;
    default:
        if (strpos($rest, '/livro/') === 0) {

            $filename = __DIR__ . "/views/livro.php";
            if (file_exists($filename)) {
                require $filename;
                break;
            }
        }
        if (strpos($rest, '/autor/') === 0) {

            $filename = __DIR__ . "/views/autor.php";
            if (file_exists($filename)) {
                require $filename;
                break;
            }
        }
        $filename = __DIR__ . "/views" . $rest . ".php";
        if (file_exists($filename)) {
            require $filename;
            break;
        }
        http_response_code(404);
        require __DIR__ . "/views/404.php";
        break;
}
