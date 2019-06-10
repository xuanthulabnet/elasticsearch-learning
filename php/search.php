<?php
use Elasticsearch\Client;
require 'vendor/autoload.php';

$hosts = [
    [
        'host' => '127.0.0.1',
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
            ],
            'highlight' => [
                 'pre_tags' => ["<strong class='text-danger'>"],
                 'post_tags' => ["</strong>"],

                'fields' => [
                            'title' =>  new stdClass(),
                            'keywords' => new stdClass()
                    ]
            ]
        ]
    ];
    $prs = $client->search($params);

//    echo "<pre>";
//    print_r($prs);
//    echo "</pre>";
    if ($prs['hits']['total'] >= 1) {
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
                <?
                    $title    = $r['highlight']['title'][0] ??  $r['_source']['title'];
                    $keywords =  $r['highlight']['keywords'] ?? $r['_source']['keywords'];

                ?>


                <p><a href="#"><?=$title?></a> <br>
                    <?=implode(',', $keywords)?></p>
                <hr>

            <?endforeach?>
        <?endif;?>

    </div>
</div>

