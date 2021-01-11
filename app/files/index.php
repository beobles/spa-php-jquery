<?php
    $header = [
        "title" => "index.php"
    ];

    if ($Router::settings()["permissionToGetContent"]) {
?>
<div class="header">index.php</div>
<div class="content">
<p>
    <pre>GET: <?= var_dump($_GET); ?></pre>
</p>
<ul>
    <li>
        <a href="<?= URL; ?>/another">another</a>
    </li>
    <li>
        <a href="<?= URL; ?>/regex/<?= rand(0, 100); ?>">regex</a>
    </li>
</ul>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>
<div class="footer">index.php</div>
<?php
    } else {
        $data = [
            "response" => "error",
            "message" => "Cannot GET this page content."
        ];

        echo json_encode($data);
    }
?>