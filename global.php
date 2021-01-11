<?php 
    define("ROOT", __dir__);
    define("PROTOCOL", isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https" : "http");

    $explodeURL = explode("/", $_SERVER["SCRIPT_NAME"]);
    $resultURL = $_SERVER["HTTP_HOST"];

    foreach ($explodeURL as $key => $value) {
        if ($key != 0 && $value != end($explodeURL)) {
            $resultURL = $resultURL . "/$value";
        }
    }

    define("URL", PROTOCOL . "://" . $resultURL);

    require_once(ROOT . "/app/deprived/class/class.core.php");
?>