<?php 
    require_once(__dir__ . "/class.router.php");

    $Router = new Router([
        "filesPath" => ROOT . "/app/files",
        "hideSourceCode" => true,
        "defaultTemplate" => "/template.php"
    ]);
?>