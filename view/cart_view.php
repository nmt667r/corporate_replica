<?php 
    $cancel =[];
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品一覧</title>
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
            <?php foreach ($err_msg as $read) {?>
            <p><?php print h($read); ?></p>
            <?php } ?>
            <?php if(isset($_SESSION['user_name']) === TRUE){?>
            <h1><?php echo $_SESSION['user_name'] ?>さんのカート</h1>
            <?php } ?>
            
            <?php if(count($cart) !== 0){?>
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
                                    <?php if($read['stock'] === 0){ 
                                    $cancel[] = 'none'; ?>
                                        <div class="stock_none red">申し訳有りませんが売り切れ中です</div>
                                    <?php }else{ ?>
                                        <div><input type="text" name="amount" class="amount_form" value=<?php print $read['amount']?>></div>
                                        <div><input type="submit"class="amount_submit" value="数量変更"></div>
                                                                   <input type="hidden" name="post_type" value="amount_change">
                                                                   <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                                    <?php }?>
                                </div>
                                </form>
                                <form method="POST">
                                <div class="delete">
                                    <input type="submit" class="delete_submit" value="カートから削除">
                                    <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                                    <input type="hidden" name="post_type" value="delete_item">
                                </div>
                                </form>
                                <div class="flex stock">
                                    <?php if($read['amount'] > $read['stock'] && $read['stock'] !== 0){
                                    $cancel[] = 'enough';?>
                                        <div class="not_enough">在庫不足です。申し訳有りませんが注文数は在庫数以下に変更願います。</div>
                                    <?php }?>
                                    <div class="stock_print">在庫数:<?php print $read['stock']?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="flex under_space">
                        <div class="total">合計金額:<?php echo $total ?>円</div>
                        <?php if(count($cancel) > 0){?>
                            <div class="buy red">カート内に問題があるようです。修正してください。</div>
                        <?php }else{ ?>
                            <form method="POST">
                                <div><input type="submit" class="buy" value="カート内アイテム購入確認"></div>
                                     <input type="hidden" name="post_type" value="verification">
                            </form>
                        <?php }?>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="left_margin">カート内にアイテムが有りません。</div>
            <?php }?>
        </div>
    </body>
</html>