<?php 
    require_once(__dir__ . "/global.php");

    $pathUrl = (isset($_GET["path"])) ? "/" . $_GET["path"] : "/"; # Você pode obter $_GET["path"] através da configuração do .htacess ou web.config

    $Router::register([
        "file" => "/index.php", # O nome do arquivo para obter o contéudo
        "route" => [
            "",
            "/",
            "/index",
            "/index/([0-9]+)" # Se conter expressão regular todas irão retornar como $_GET em ordem numérica. Ex: $_GET["arg_0"]
        ]
    ]);

    $Router::register([
        "file" => "/another.php",
        "route" => [
            "/another"
        ]
    ]);

    $Router::register([
        "file" => "/regex.php",
        "route" => [
            "/regex",
            "/regex/([0-9]+)"
        ]
    ]);

    $Router::get($pathUrl); # Verifica as rotas registradas mediante ao URL obtido, caso exista vai retornar o contéudo da mesma.
?>