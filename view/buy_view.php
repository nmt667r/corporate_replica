<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品一覧</title>
        <link rel="stylesheet" href="./css/html5reset-1.6.1.css">
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/cart.css">

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
                    <?php foreach ($cart as $read) {?>
                        <div class="frame flex">
                            <div class="img_box">
                                <div class="img"><img src="<?php print h(IMG_DIR . $read['img']); ?>"></div>
                            </div> 
                            <div class="var_box">
                                <div class="flex">
                                    <div class="name"><?php print h($read['item_name']); ?></div>
                                    <div class="amount_print">注文数：<?php print h($read['amount']); ?>個</div>
                                </div>
                                <form method="POST">
                                <div class="flex belly">
                                    <div class="price"><?php print $read['price']; ?>円</div>
                                </div>
                                </form>
                                <form method="POST">
                                
                                </form>
                                <div class="flex stock">
                                    <div class="stock_print">在庫数:<?php print $read['stock']?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="flex under_space">
                        <div class="total">合計金額:<?php echo $total ?>円</div>
                        <form method="POST">
                            <div class="var">
                                <input type="submit" class="buy" value="購入確定">    
                                <input type="hidden" name="post_type" value="buy">
                            </div>
                        </form>
                    </div>
                </div>
    </body>
</html>