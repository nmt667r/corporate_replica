<?php

/**ヘッダー欄用*/
    function get_header(){
        $header = [];
        if($_SESSION['user_name'] === 'admin'){
            $header[0]['name'] = '購入履歴';
            $header[0]['link'] = './history.php';
            $header[1]['name'] = 'カート';
            $header[1]['link'] = './cart.php';
            $header[2]['name'] = '商品一覧';
            $header[2]['link'] = './itemlist.php';
            $header[3]['name'] = '商品管理';
            $header[3]['link'] = './admin.php';
            $header[4]['name'] = 'ユーザーリスト'; 
            $header[4]['link'] = './user_list.php';
            $header[5]['name'] = '全売却履歴';
            $header[5]['link'] = './all_history.php';
        }else{
            $header[0]['name'] = '購入履歴';
            $header[0]['link'] = './history.php';
            $header[1]['name'] = 'カート';
            $header[1]['link'] = './cart.php';
            $header[2]['name'] = '商品一覧';
            $header[2]['link'] = './itemlist.php';
            
        }
        return $header;
    }
    
/**カテゴリ変更*/
    function get_all_category(){
        $category_all = [];
        $category_all = array('野球','サッカー','テニス','バスケットボール','バレーボール','バドミントン','陸上競技','モータースポーツ','その他');
        return $category_all;
    }
?>


   