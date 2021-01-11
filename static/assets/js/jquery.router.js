/* 
    Criado por dut em 10/01/2021

    - Este plugin tem como objetivo principal obter o contéudo das rotas registradas por meio de um outro router interno.

    ---------

    Discord: dut#3031
    Twitter: @beobles
    Instagram: @beobles
*/

(function(global, $) {
    var routerIsReady = false,
    readyToRun = false,

    mainContainer = null,

    firstRun = true,
    replaceContentBeforeRun = [],

    onReady = null,

    onBeforeRun = null,
    readyAfterRun = false,
    onAfterRun = null,

    onBeforeError = null,
    onError = null,
    onAfterError = null,

    onBeforeLoad = null,
    onLoad = null,

    intervals = {},

    pageInfo = {
        title: null,
        href: null
    }

    $.fn.router = function(options) {
        if ($(this).length > 0) {
            mainContainer = $(this).first();

            if (typeof options != "undefined") {
                if (typeof options.replaceContentBeforeRun != "undefined") {
                    for (var i = 0; i < options.replaceContentBeforeRun.length; i++) {
                        if (options.replaceContentBeforeRun[i].length > 0) {
                            replaceContentBeforeRun.push(options.replaceContentBeforeRun[i]);
                        }
                    }
                }
            }

            var checkOnReady = setInterval(function() {
                if (typeof onReady == "function") {
                    clearInterval(checkOnReady);

                    routerIsReady = true;
                    readyToRun = true;

                    onReady();
                } else {
                    if (onReady == false) {
                        clearInterval(checkOnReady);

                        routerIsReady = true;
                        readyToRun = true;
                    }
                }
            }, 100);

            return {
                on: $.router.on,
                run: $.router.run,
            };
        } else {
            console.error("Não foi possivel encontrar o elemento para carregar o contéudo das rotas.");

            return {
                on(order, func) {},

                run(href, isPopState = false) {}
            }
        }
    }

    $.router = function(container, options) {
        if (typeof container == "undefined") {
            console.error("Nenhum elemento para carregar o conteúdo das rotas foi definido.");

            return {
                on(order, func) {},

                run(href, isPopState = false) {}
            }
        } else {
            if ($(document).find(container).length > 0) {
                mainContainer = $(document).find(container).first();

                if (typeof options != "undefined") {
                    if (typeof options.replaceContentBeforeRun != "undefined") {
                        for (var i = 0; i < options.replaceContentBeforeRun.length; i++) {
                            if (options.replaceContentBeforeRun[i].length > 0) {
                                replaceContentBeforeRun.push(options.replaceContentBeforeRun[i]);
                            }
                        }
                    }
                }

                var checkOnReady = setInterval(function() {
                    if (typeof onReady == "function") {
                        clearInterval(checkOnReady);

                        routerIsReady = true;
                        readyToRun = true;

                        onReady();
                    } else {
                        if (onReady == false) {
                            clearInterval(checkOnReady);

                            routerIsReady = true;
                            readyToRun = true;
                        }
                    }
                }, 100);

                return {
                    on: $.router.on,
                    run: $.router.run
                }
            } else {
                throw new Error("Não foi possivel encontrar o elemento para carregar o contéudo das rotas.");
            }
        }
    }

    $.router.on = function(order, func) {
        if (onReady == null) {
            onReady = false;
        }

        if (onBeforeRun == null) {
            onBeforeRun = false;
        }

        if (onAfterRun == null) {
            onAfterRun = false;
        }

        if (onLoad == null) {
            onLoad = false;
        }

        if (onBeforeError == null) {
            onBeforeError = false;
        }

        if (onError == null) {
            onError = false;
        }

        if (onAfterError == null) {
            onAfterError = false;
        }

        if (typeof order == "undefined") return false;
        if (typeof func != "function") return false;

        if (order == "ready") {
            onReady = func;
        } else if (order == "beforeRun") {
            onBeforeRun = func;
        } else if (order == "afterRun") {
            onAfterRun = func;
        } else if (order == "beforeLoad") {
            onBeforeLoad = func;
        } else if (order == "load" || order == "afterLoad") {
            onLoad = func;
        } else if (order == "beforeError") {
            onBeforeError = func;
        } else if (order == "error") {
            onError = func;
        } else if (order == "afterError") {
            onAfterError = func;
        }

        return {
            on: $.router.on,
            run: $.router.run
        }
    }

    $.router.run = function(href, isPopState = false) {
        $.router.on();

        if (!readyToRun) {
            var checkReadyToRun = setInterval(function() {
                if (readyToRun == true) {
                    clearInterval(checkReadyToRun);

                    $.router.run(href);
                }
            }, 100);
        } else {
            if (document.location.href == href && !firstRun && (isPopState && document.location.href == pageInfo.href) ? true : false) return false;

            if (typeof onBeforeRun == "function") {
                onBeforeRun();
            }

            $.ajax({
                url: href,
                type: "POST",
                data: {
                    url: href
                },
                dataType: "json"
            }).done(function(data) {
                if (data.response == "redirect") {
                    $.router.run(data.href);
                } else {
                    if (data.response == "success") {
                        if (typeof data.content == "undefined") return false;

                        if (replaceContentBeforeRun.length > 0 && !firstRun) {
                            var hasError = false;

                            $("body").append($("<div data-temp-content />").hide().html(data.content));

                            if (typeof onBeforeLoad == "function") {
                                onBeforeLoad();
                            }

                            for (var i = 0; i < replaceContentBeforeRun.length; i++) {
                                if (hasError) break;

                                if (mainContainer.find(replaceContentBeforeRun[i]).length > 0 && $("body > div[data-temp-content]").find(replaceContentBeforeRun[i]).length > 0) {
                                    mainContainer.find(replaceContentBeforeRun[i]).html($("[data-temp-content]").find(replaceContentBeforeRun[i]).html()).promise().catch(function() {
                                        hasError = true;
                                    });
                                }
                            }

                            $("body").find("[data-temp-content]").remove();

                            if (!hasError) {
                                firstRun = false;

                                pageInfo.title = (typeof data.title == "undefined") ? null : data.title;
                                pageInfo.href = (typeof data.href == "undefined") ? null : data.href;

                                document.title = pageInfo.title;
                                history.pushState(null, pageInfo.title, pageInfo.href);

                                if (typeof onLoad == "function") {
                                    onLoad();
                                }
                            } else {
                                if (typeof onBeforeError == "function") {
                                    onBeforeError();
                                }

                                if (typeof onError == "function") {
                                    onError();
                                } else {
                                    throw new Error("Um erro inesperado aconteceu, talvez possa resolve-lo recarregando a página.");
                                }

                                if (typeof onAfterError == "function") {
                                    onAfterError();
                                }
                            }

                            readyAfterRun = true;
                        } else {
                            if (typeof onBeforeLoad == "function") {
                                onBeforeLoad();
                            }

                            mainContainer.html(data.content).promise().then(function() {
                                firstRun = false;

                                pageInfo.title = (typeof data.title == "undefined") ? null : data.title;
                                pageInfo.href = (typeof data.href == "undefined") ? null : data.href;

                                document.title = pageInfo.title;
                                history.pushState(null, pageInfo.title, pageInfo.href);

                                if (typeof onLoad == "function") {
                                    onLoad();
                                }
                            }).catch(function() {
                                if (typeof onBeforeError == "function") {
                                    onBeforeError();
                                }

                                if (typeof onError == "function") {
                                    readyAfterRun = true;

                                    onError();
                                } else {
                                    readyAfterRun = true;

                                    throw new Error("Um erro inesperado aconteceu, talvez possa resolve-lo recarregando a página.");
                                }

                                if (typeof onAfterError == "function") {
                                    onAfterError();
                                }
                            });

                            readyAfterRun = true;
                        }
                    } else {
                        if (typeof onBeforeError == "function") {
                            onBeforeError();
                        }

                        if (typeof onError == "function") {
                            readyAfterRun = true;

                            onError();
                        } else {
                            readyAfterRun = true;

                            throw new Error("Um erro inesperado aconteceu, talvez possa resolve-lo recarregando a página.");
                        }

                        if (typeof onAfterError == "function") {
                            onAfterError();
                        }
                    }
                }
            }).fail(function() {
                if (typeof onBeforeError == "function") {
                    onBeforeError();
                }

                if (typeof onError == "function") {
                    readyAfterRun = true;

                    onError();
                } else {
                    readyAfterRun = true;

                    throw new Error("Um erro inesperado aconteceu, talvez possa resolve-lo recarregando a página.");
                }

                if (typeof onAfterError == "function") {
                    onAfterError();
                }
            });

            if (typeof onAfterRun == "function") {
                var waitForBeforeRun = setInterval(function() {
                    if (readyAfterRun == true) {
                        clearInterval(waitForBeforeRun);
                        readyAfterRun = false;

                        onAfterRun();
                    }
                });
            }
        }
    }
})(this, window.jQuery);