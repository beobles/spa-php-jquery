<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= (isset($header["title"])) ? $header["title"] : null; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
<?= isset($header["description"]) ? "\t\t<meta name=\"description\" content=\"{$header["description"]}\">\n" : ""; ?>
        <meta name="keywords" content="">

        <meta property="og:title" content="<?= (isset($header["title"])) ? $header["title"] : null; ?>">
        <meta property="og:site_name" content="Sitename">
<?= isset($header["description"]) ? "\t\t<meta property=\"og:description\" content=\"{$header["description"]}\">\n" : ""; ?>

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@Sitename">
        <meta name="twitter:creator" content="@Sitename">
        <meta name="twitter:title" content="<?= (isset($header["title"])) ? $header["title"] : null; ?>">
<?= isset($header["description"]) ? "\t\t<meta name=\"twitter:description\" content=\"{$header["description"]}\">\n" : ""; ?>
    </head>
    <body>
        <div class="app-content">${app.content}</div>
    </body>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?= URL; ?>/static/assets/js/jquery.router.js"></script>
    <script type="text/javascript">
        $.router(".app-content", {
            replaceContentBeforeRun: [
                ".content"
            ]
        }).run(document.location.href);

        $(document).on("click", "a", function(e) {
            var hrefItem = $(this),
            hrefUrl = (typeof hrefItem.attr("href") != "undefined") ? hrefItem.attr("href") : false,
            hrefTarget = (typeof hrefItem.attr("target") != "undefined") ? hrefItem.attr("target") : false;

            if (hrefUrl != false && hrefUrl.length > 0) {
                if (hrefTarget != "_blank") {
                    e.preventDefault();

                    if (hrefUrl != document.location.href) {
                        $.router.run(hrefUrl);
                    }
                }
            }
        }).on("submit", "form", function(e) {
            e.preventDefault();
        });

        $(window).on("popstate", function(e) {
            $.router.run(document.location.href, true);
        });
    </script>
</html>