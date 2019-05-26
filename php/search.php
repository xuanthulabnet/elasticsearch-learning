<?php
use Elasticsearch\Client;
require 'vendor/autoload.php';

$hosts = [
    [
        'host' => 'localhost',
        'port' => '9200',
        'scheme' => 'http',
    ]
];

$client = \Elasticsearch\ClientBuilder::create()
    ->setHosts($hosts)
    ->build();


$search = $_POST['search'] ?? null;
$rs = null;


if ($search != null) {
    $params = [
        'index' => 'article',
        'type' => 'article_type',
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        ['match' => ['title' => $search]],
                        ['match' => ['keywords' => $search]],
                    ]
                ]
            ]
        ]
    ];


    $prs = $client->search($params);
    if ($prs['hits']['total']['value'] >= 1) {
        $rs = $prs['hits']['hits'];
    }
}


?>
<div class="card m-4">
    <div class="card-header display-4 text-danger">Tìm kiếm</div>
    <div class="card-body">

        <form method="post" class="form-inline">
            <div class="form-group">
                <input name="search" value="<?=$search?>" class="form-control">
                <input type="submit" value="Tìm kiếm" class="form-control btn btn-danger ml-2">
            </div>
        </form>

        <hr>

        <?if ($rs != null):?>
            <h3>Kết quả tìm kiếm: <?=$search?></h3>
            <hr>
            <?foreach ($rs as $r):?>

                <p><a href="#"><?=$r['_source']['title']?></a> <br>
                    <?=implode(',', $r['_source']['keywords'])?></p>
                <hr>

            <?endforeach?>
        <?endif;?>

    </div>
</div>

