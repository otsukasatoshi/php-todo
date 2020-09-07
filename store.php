<?php

// 汎用関数機能が定義されたファイルを読み込む
require_once('functions.php');

// function.phpのsavePostedData関数が呼ばれる($_POSTで入力された値を引数にとる)
savePostedData($_POST);
// 各処理が終わったらindexページは遷移
header('Location: ./index.php');
