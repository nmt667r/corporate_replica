<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品管理</title>
        
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/admin.css">
    </head>
    <body>
        <header>
            <div class="center_area">
                <div class="flex">
                    <div class="logo"><img src="./css/logo0.png"></div>
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
            <h1>商品管理ページ</h1>
            <div class="compmsg"><?php echo $comp ?></div>
            <?php foreach ($err_msg as $read) { ?>
                <div class="errmsg red"><?php print $read; ?></div>
            <?php } ?>
            <div class="addh1">新規商品登録</div>
            <form enctype="multipart/form-data" method="post">
                <div class="add">
                    <div class="flex add_frame">
                        <div class="">
                            <div class="add_form">名前：<input type="text" name="item_name"></div>
                            <div class="add_form">値段：<input type="text" name="price"></div>
                            <div class="add_form">個数：<input type="text" name="stock"></div>
                        </div>
                        <div class="add_center">
                            <div class="add_form">画像：<input type="file" name="img"></div>
                            <div class="add_form">公開設定：
                                <select name="status">
                　                   <option value="0">非公開</option>
                　                   <option value="1">公開</option>
                　                   <option value="3">テスト</option>
                                </select>
                            </div>
                            <div class="add_form">カテゴリ：
                                <select name="category">
                                    <?php foreach ($category_all as $read3) { ?>
                                        <option value="<?php echo h($read3) ?>"><?php echo h($read3) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="add_comennt_frame">
                            <div class="any">任意記入欄(文字数～20)</div>
                            <div class="comment_box1"><input type="text" class="comment_form" name="comment1">コメント1(商品の概要)</div>
                            <div class="comment_box2"><input type="text" class="comment_form" name="comment2">コメント2(その他雑記)</div> 
                        </div>
                    </div>
                </div>
                <div class="add_submit">
                        <input type="submit" name="submit" value="商品追加">
                        <input type="hidden" name="post_type" value="add">
                </div>
            </form>
            <h2>商品情報変更</h2>
            <?php foreach ($deta as $read) { ?>
                <div class="frame flex">
                    <div class="img"><img src="<?php print h(IMG_DIR . $read['img']); ?>"></div>
                    <div class="name_price">
                        <div class="item_name"><?php print h($read['item_name']); ?></div>
                        <div class="price"><?php print $read['price']; ?>円</div>
                    </div>
                    <div class="stock">
                        <form method="POST">
                            <div><input type="text" name="stock" class="stock_head" value=<?php print $read['stock']?>></div>
                            <div class="stock_body">個</div>
                            <div><input type="submit" class="stock_foot" value="変更"></div>
                                                    <input type="hidden" name="post_type" value="stock_change">
                                                    <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                        </form>
                    </div>
                    <div class="category">
                        <form method="POST">
                            <div>カテゴリ：<?php print $read['category']?></div>
                            <div>
                                <select name="category">
                                <?php foreach ($category_all as $read2) { ?>
                                    <option value="<?php echo h($read2) ?>"><?php echo h($read2) ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div><input type="submit" class="cat_foot" value="変更"></div>
                                 <input type="hidden" name="post_type" value="category_change">
                                 <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                        </form>
                    </div>
                    <div class="comennt_box">
                        <div class="comment">
                            <form method="POST">
                                <div><input type="text" name="comment1" class="com1" value=<?php print h($read['comment1'])?>></div>
                                <div>コメント1を<input type="submit" class="com_submit" value="更新"></div>
                                     <input type="hidden" name="post_type" value="comment_fix1">
                                     <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                            </form>
                        </div>
                        <div class="comment">
                            <form method="POST">
                                <div><input type="text" name="comment2" class="com2" value=<?php print h($read['comment2'])?>></div>
                                <div>コメント2を<input type="submit" class="com_submit" value="更新"></div>
                                     <input type="hidden" name="post_type" value="comment_fix2">
                                     <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                            </form>    
                        </div>
                    </div>
                    <div class="status_delete">
                        <div class="status">
                            <form method="POST">
                                <?php if($read['status'] === 1){?><div class="status_print"><?php print "公開"?></div><?php }else{?><div class="status_print red"><?php print"非公開"?></div><?php }?>
                                <div><input type="submit" class="status_submit" value="<?php if($read['status'] === 1){print "公開→非公開";}else{print"非公開→公開";}?> "></div>
                                <input type="hidden" name="status" value="<?php echo $read['status']?>">
                                <input type="hidden" name="post_type" value="status_change">
                                <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                            </form>
                        </div>
                        <div>
                            <form method="POST">
                                <input type="submit" class="delete_submit" value="削除">
                                <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
                                <input type="hidden" name="post_type" value="delete_item">
                            </form>
                        </div>
                    </div>
                </div>  
            <?php } ?>
        </div>
    </body>
</html>