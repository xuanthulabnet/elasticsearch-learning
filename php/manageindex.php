<?php
use Elasticsearch\Client;

require "vendor/autoload.php";

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

/*

    Document {
        title: data,
        content: data,
        keywords: data
    }


    article_type:
       title: string|analyzer=
 */


$params = [
    'index' => 'article',

];
 $client->cat()->indices();



$act = $_GET['act'] ?? null;
$mgs = 'Chọn lệnh tạo hoặc xóa';
if ($act == 'create') {
    //Tạo Index: article
    $params = [
        'index' => 'article'
    ];

    $exist = $client->indices()->exists($params);
    if ($exist) {
        $mgs = "Index - article đã tồn tại - không cần tạo";
    }
    else {


        $params = [
            'index' => 'article',

            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                    'analysis' => [
                        'analyzer' => [ //Lọc loại bỏ thẻ html và index  chuyển đổi không dấu, chữ in thường
                            'my_analyzer' => [
                                'type' => 'custom',
                                'tokenizer' => 'icu_tokenizer',
                                "char_filter" => [ "html_strip"],
                                'filter' => ['icu_folding', 'lowercase', 'stop'] //
                            ],

                        ]
                    ],

                ],
            ],

        ];

       $response = $client->indices()->create($params);


        $params = [
            'index' => 'article',
            'type' => 'article_type',
            'include_type_name' => true,
            'body' => [
                'article_type' => [

                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'my_analyzer'
                        ],

                    ]
                ]
            ]
        ];



        $response = $client->indices()->putMapping($params);

        $mgs = "Index - articl mới được tạo";
    }



}
else if ($act == 'delete') {
    // Xóa index:article
    $params = [
        'index' => 'article'
    ];

    $exist = $client->indices()->exists($params);
    if ($exist) {
        $rs = $client->indices()->delete($params);
        $mgs = "Đã xóa index - article";
    }
    else {
        $mgs = "Index - articl không tồn tại";
    }

}


$exist = $client->indices()->exists(['index' => 'article']);

?>

<div class="card m-4">
    <div class="card-header display-4 text-danger">Quản lý Index</div>
    <div class="card-body">
        <? if (!$exist):?>
            <a href="http://localhost:8888/?page=manageindex&act=create" class="btn btn-primary">Tạo index <strong>article</strong></a>
        <? else:?>
            <a href="http://localhost:8888/?page=manageindex&act=delete" class="btn btn-danger">Xóa index <strong>article</strong></a>
        <? endif;?>

        <div class="alert alert-danger mt-4"><?=$mgs?></div>
    </div>
</div>