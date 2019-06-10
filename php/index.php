<?php
    $page = $_GET['page'] ?? '';

    $menuitems = [
         'manageindex' => 'Quản lý Index',
         'document' => 'Cập nhật Document',
         'search' => 'Tìm kiếm',

    ];

?>


<html>
    <head>
        <title>Thực hành Elasticsearch với PHP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

<!--
    /                    trang index.php
    /?page=manageindex   quan ly ES index
    /?page=document      luu cap nhat Document
    /?page=search        tim kiem tren ES

-->

    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-danger">

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>

                    <? foreach ($menuitems as $url => $label):?>

                        <?php
                            $activeclass='';
                            if ($page == $url)
                                $activeclass = 'active';
                        ?>

                        <li class="nav-item">
                            <a class="nav-link <?=$activeclass?>" href="/?page=<?=$url?>"><?=$label?></a>
                        </li>
                    <?endforeach;?>
                </ul>
            </div>
        </nav>

        <?php if ($page == ''):?>
            <p class="display-4 alert alert-danger">Thực hành Elasticsearch - xuanthulab.net</p>
        <?else:?>
            <?php include $page.'.php'; ?>
        <?endif;?>

    </body>
</html>