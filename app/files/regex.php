<?php 
    $header = [
        "title" => "regex.php"
    ];

    if ($Router::settings()["permissionToGetContent"]) {
?>
<div class="header">regex.php</div>
<div class="content">
<p>
    <pre>GET: <?= var_dump($_GET); ?></pre>
</p>
<ul>
    <li>
        <a href="<?= URL; ?>/">index</a>
    </li>
    <li>
        <a href="<?= URL; ?>/another">another</a>
    </li>
</ul>
<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
</div>
<div class="footer">regex.php</div>
<?php
    } else {
        $data = [
            "response" => "error",
            "message" => "Cannot GET this page content."
        ];

        echo json_encode($data);
    }
?>