<?php 
    $header = [
        "title" => "another.php"
    ];

    if ($Router::settings()["permissionToGetContent"]) {
?>
<div class="header">another.php</div>
<div class="content">
<p>
    <pre>GET: <?= var_dump($_GET); ?></pre>
</p>
<li>
        <a href="<?= URL; ?>/">index</a>
    </li>
    <li>
        <a href="<?= URL; ?>/regex/<?= rand(0, 10); ?>">regex</a>
    </li>
<p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
</div>
<div class="footer">another.php</div>
<?php
    } else {
        $data = [
            "response" => "error",
            "message" => "Cannot GET this page content."
        ];

        echo json_encode($data);
    }
?>