<?php
    if(isset($_SESSION['user_name']) !== TRUE){
        header('Location: ../login.php');
        exit;
    }
?>

<!DOCTYPE>
<html lang="ja">
    <head>
        <meta charset="ja">
        <title>購入完了ページ</title>
        <link rel="stylesheet" href="./css/html5reset-1.6.1.css">
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/finish.css">
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
            <h1>購入完了</h1>
            <?php foreach ($cart as $read) { ?>
            <div class="flex frame">
                <div class="img_box"><img src="<?php print h(IMG_DIR . $read['img']); ?>"></div>
                <div class="var_box">
                    <div class="name"><?php print h($read['item_name']); ?></div>
                    <div class="belly flex">
                        <div class="price"><?php print $read['price']; ?>円</div>
                        <div class="amount"><?php print $read['amount']; ?>個</div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <h2>合計金額<?php echo $total ?>円</h2>
            <p class="comp">の入金を確認しました。</p>
            <p class="move"><a href="./itemlist.php">商品ページに移動する。</a></p>
        </div>
    </body>
</html>