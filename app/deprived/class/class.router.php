<?php 
    class Router {
        public static $routerSettings = [
            "filesPath" => null,
            "hideSourceCode" => false,
            "defaultTemplate" => null,
            "defaultErrorHref" => "/404",
            "permissionToGetContent" => false
        ];

        public static $routes = [];

        function __construct($routerConfigArr) {
            if (!isset($routerConfigArr) && gettype($routerConfigArr) != "array") {
                throw new Exception("Ocorreu um erro ao obter a array para as configurações das rotas.");
            }

            if (isset($routerConfigArr["filesPath"])) {
                if (is_dir($routerConfigArr["filesPath"])) {
                    self::$routerSettings["filesPath"] = $routerConfigArr["filesPath"];
                } else {
                    throw new Exception("Não foi possivel encontrar o diretório para arquivos das rotas.");
                }
            } else {
                throw new Exception("Nenhum diretório para os arquivos das rotas foi definido.");
            }

            if (isset($routerConfigArr["hideSourceCode"]) && gettype($routerConfigArr["hideSourceCode"]) == "boolean") {
                self::$routerSettings["hideSourceCode"] = $routerConfigArr["hideSourceCode"];
            } else {
                throw new Exception("Tipo inválido para alternar entre esconder ou não o código fonte.");
            }

            if (isset($routerConfigArr["defaultTemplate"])) {
                if (file_exists(self::$routerSettings["filesPath"] . $routerConfigArr["defaultTemplate"])) {
                    self::$routerSettings["defaultTemplate"] = $routerConfigArr["defaultTemplate"];
                } else {
                    throw new Exception("Não foi possivel encontrar o template principal.");
                }
            } else {
                throw new Exception("Nenhum template principal foi definido.");
            }
        }
        
        static function register($routeArr) {
            if ($routeArr["file"] != null && $routeArr["route"] != null) {
                if (gettype($routeArr["file"]) == "string" && file_exists(self::$routerSettings["filesPath"] . $routeArr["file"]) ? true : false && gettype($routeArr["route"]) == "string" && strlen($routeArr["route"]) > 0 ? true : gettype($routeArr["route"]) == "array" && count($routeArr["route"]) > 0 ? true : false) {
                    self::$routes[] = $routeArr;
                }
            }
        }

        static function get($urlRoute, $onlyFind = false) {
            for ($i = 0; $i < count(self::$routes); $i++) {
                $routeObj = self::$routes[$i];

                if (gettype($routeObj["route"]) == "array") {
                    for ($j = 0; $j < count($routeObj["route"]); $j++) { 
                        $foundedMatches = preg_match("#^" . $routeObj["route"][$j] . "$#", $urlRoute, $matches);

                        if ($foundedMatches && !$onlyFind) {
                            array_shift($matches);

                            if (count($matches) > 0) {
                                for ($k = 0; $k < count($matches); $k++) { 
                                    $_GET["arg_" . $k] = $matches[$k];
                                }
                            }

                            if (file_exists(self::$routerSettings["filesPath"] . $routeObj["file"])) {
                                $template = "";
                                $content = "";

                                self::$routerSettings["permissionToGetContent"] = true;

                                global $Router;

                                ob_start();

                                include(self::$routerSettings["filesPath"] . $routeObj["file"]);
                                $content = ob_get_contents();

                                ob_end_clean();

                                ob_start();

                                include(self::$routerSettings["filesPath"] . self::$routerSettings["defaultTemplate"]);
                                $template = ob_get_contents();

                                ob_end_clean();

                                if (extract($_POST)) {
                                    $data = [
                                        "response" => "success",
                                        "title" => @$header["title"],
                                        "href" => $url,
                                        "content" => $content
                                    ];

                                    echo json_encode($data);

                                    header("Content-Type: application/json");

                                    return true;
                                } else {
                                    if (!self::$routerSettings["hideSourceCode"]) {
                                        $content = "\n$content\t\t";
                                    } else {
                                        $content = "";
                                    }
                                }

                                echo str_replace("\${app.content}", $content, $template);

                                self::$routerSettings["permissionToGetContent"] = false;
                            }

                            return true;
                        }
                    }
                } else {
                    $foundedMatches = preg_match("#^" . $routeObj["route"] . "$#", $urlRoute, $matches);

                    if ($foundedMatches && !$onlyFind) {
                        array_shift($matches);

                        if (count($matches) > 0) {
                            for ($k = 0; $k < count($matches); $k++) { 
                                $_GET["arg_" . $k] = $matches[$k];
                            }
                        }

                        if (file_exists(self::$routerSettings["filesPath"] . $routeObj["file"])) {
                            $template = "";
                            $content = "";

                            self::$routerSettings["permissionToGetContent"] = true;

                            global $Router;

                            ob_start();

                            include(self::$routerSettings["filesPath"] . $routeObj["file"]);
                            $content = ob_get_contents();

                            ob_end_clean();

                            ob_start();

                            include(self::$routerSettings["filesPath"] . self::$routerSettings["defaultTemplate"]);
                            $template = ob_get_contents();

                            ob_end_clean();

                            if (extract($_POST)) {
                                $data = [
                                    "response" => "success",
                                    "title" => @$header["title"],
                                    "href" => $url,
                                    "content" => $content
                                ];

                                echo json_encode($data);

                                header("Content-Type: application/json");

                                return true;
                            } else {
                                if (!self::$routerSettings["hideSourceCode"]) {
                                    $content = "\n$content\t\t";
                                } else {
                                    $content = "";
                                }
                            }

                            echo str_replace("\${app.content}", $content, $template);

                            self::$routerSettings["permissionToGetContent"] = false;
                        }

                        return true;
                    }
                }
            }

            if (!$onlyFind) {
                if (!empty(self::$routerSettings["defaultErrorHref"]) && self::get(self::$routerSettings["defaultErrorHref"], true)) {
                    self::redirect(self::$routerSettings["defaultErrorHref"]);
                } else {
                    header("HTTP/1.0 404 Not Found");
                }
            }
        }

        static function redirect($href) {
            ob_start();

            if (extract($_POST)) {
                $data = [
                    "response" => "redirect",
                    "href" => "$href"
                ];

                echo json_encode($data);

                header("Content-Type: application/json");
            } else {
                $href = ($href == "refresh") ? "Refresh: 0" : "Location: $href";

                header($href);
            }

            exit;

            ob_end_flush();
        }

        static function settings() {
            return self::$routerSettings;
        }
    }
?>