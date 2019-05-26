<?php
use Elasticsearch\Client;

require "vendor/autoload.php";

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
$params = [
    'index' => 'article'
];


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
        $rs = $client->indices()->create($params);
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


$exist = $client->indices()->exists($params);

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