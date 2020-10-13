<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品一覧</title>
        <link rel="stylesheet" href="./css/html5reset-1.6.1.css">
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/itemlist.css">
        <style>
            .red{color:red;}
         </style>
    </head>
    <body>
        <header>
            <div class="center_area">
                <div class="flex">
                    <div class="logo"><img src="./css/logo.png"></div>
                    <div class="right_box">
                        <div class="flex nav">
                            <p>
                                <form action="./logout.php">
                                    <input type="submit" value="ログアウト">
                                </form>
                            </p>
                            <?php foreach ($header as $read) { ?>
                                <p class="nav_jr"><a href="<?php print $read['link']?>" class="deco_delete"><?php print $read['name']?></a></p>
                            <?php } ?>
                        </div>
                        <div class="user_name"><p class="nav_jr"><?php echo $_SESSION['user_name']?>さん</p></div>
                    </div>    
                </div>
            </div>
        </header>
        <div class="center_area">
            <?php foreach ($err_msg as $read) { ?>
            <p><?php print $read; ?></p>
            <?php } ?>
            <div>
                <h1>商品一覧</h1>
                <form method="POST">
                <div class="flex change_erea">
                        <div class="change_list flex wrap">
                            <?php foreach ($category_all as $read2){?>
                                <div class="category_radio"><input type="radio" name="category" value="<?php echo $read2 ?>"><?php echo $read2 ?></div>
                            <?php } ?>
                        </div>
                    <div class="search">
                        <input type="text" name="search" class="search_form" placeholder="アイテム名">
                        <input type="hidden" name="post_type" value="list_change">
                        <input type="submit" value="詳細検索">
                    </form>
                    </div>
                </div>
            </div>
            <?php if(count($deta) !== 0){?>
                <div class="flex wrap">
                <?php foreach ($deta as $read) { ?>
                    <div class="frame flex">
                        <div class="flex">
                            <p class="img"><img src="<?php print h(IMG_DIR . $read['img']); ?>"></p> 
                        </div>
                        <div class="item_about left_margin">
                            <p class="item_name"><?php print h($read['item_name']); ?></p>
                            <div class="flex">
                                <p class="price"><?php print $read['price']; ?>円</p>
                                <p class="cart">
                                    <form method="POST">
                                        <?php if($read['stock'] === 0){ ?>
                                            <p class="red">売り切れ</p>
                                        <?php }?>
                                    </form>
                                </p>
                            </div>
                            <p class="comment1"><?php print h($read['comment1'])?></p>
                            <p class="comment2"><?php print h($read['comment2'])?></p>
                            <p class="category">カテゴリ：<?php print h($read['category'])?></p>
                            <form method="POST">
                            <?php if($read['stock'] === 0){ ?>
                                
                            <?php }else{ ?>
                            <div class="flex amount">
                                <p class="amount_form"><input type="text" name="amount" class="amount_form" value="1"></p>
                                <p class="amount_font">個</p>
                                <p><input type="submit" value="購入"></p>
                                   <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                                   <input type="hidden" name="post_type" value="add_cart">
                            </div>
                            <?php }?>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            <?php }else{ ?>
            <div class="none">指定の条件に当てはまる商品は有りませんでした。</div>
            <div class="back"><a href="./itemlist.php">全アイテムリスト一覧に戻る</a></div>
            <?php } ?>
            </div>
        </body>
    </html>     
