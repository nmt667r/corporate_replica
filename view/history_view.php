<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品一覧</title>
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/history.css">
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
            <h1><?php echo $_SESSION['user_name'] ?>さんの購入履歴</h1>
            <div class="center_area">
            <?php if(count($history) !== 0){?>
                <?php foreach ($history as $read) { ?>
                    <div class="frame flex">
                        <div class="img"><img src="<?php echo h(IMG_DIR . $read['img']); ?>"></div>
                        <div class="history_box">
                            <div class="day"><?php print h($read['create_datetime']); ?></div>
                            <div class="name"><?php print h($read['item_name']); ?></div>
                            <div class="item_about flex">
                                <div class="price"><?php print h($read['price']); ?>円</div>
                                <div class="amount"><?php print h($read['amount']); ?>個</div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="none">商品をまだ購入していません。</div>
            <?php } ?>
            </div>
        </div>
    </body>
</html>