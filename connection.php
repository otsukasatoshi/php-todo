<?php

// config.phpを読み込む
require_once('config.php');


// DBへ接続する関数
function connectPdo()
{
    try {
        // PDOクラスのインスタンス化。config.phpで定義した定数を引数にとる。
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        // 接続できなかったらエラーメッセージを投げる。
        echo $e->getMessage();
        exit();
    }
}


// 新規作成する関数
function createTodoData($todoText)
{
    // DB接続。インスタンス化されたPDOクラスを$dbhへ代入。PDOクラスをインスタンス化したものがここに返る。
    $dbh = connectPdo();
    // 実行するsql文。todosテーブルのtodoカラムにbindvalueするもの(:todotext)を追加。SQLを実行する前に、あらかじめ、そこに入れる値を用意しておく。
    $sql = 'INSERT INTO todos (todo) VALUES (:todoText)';
    // pdoクラスのprepareメソッドでsql文の実行準備。PDOクラスのprepareメソッドを実行するとPDOStatementオブジェクトが返る。
    $stmt = $dbh->prepare($sql);
    // PDOStatementオブジェクトのbindvalueメソッドで:todoText(プレースホルダ)に$todotext(入力された)を型を指定して結びつける。
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR);
    // SQLを実行する。
    $stmt->execute();
    // 処理が終わったらfunctions.phpのsavePostedData関数へ戻る。
}


// データ一覧を取得する関数
function getAllRecords()
{
    // DB接続。インスタンス化されたPDOクラスを$dbhへ代入。PDOクラスをインスタンス化したものがここに返る。
    $dbh = connectPdo();
    // 実行するsql文(todosテーブルから論理削除していないデータを全件取得)。
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    // 1回だけ使用するようなSQL文をデータベースへ送信するにはPDOクラスで用意されている"query"メソッドを使用。
    // PDOStatementのfetchAllメソッドで全件取得、最後にreturn。
    return $dbh->query($sql)->fetchAll();
}


// 更新する関数
function updateTodoData($post)
{
    // DB接続。インスタンス化されたPDOクラスを$dbhへ代入。PDOクラスをインスタンス化したものがここに返る。
    $dbh = connectPdo();
    // todosテーブルのidカラムがPOSTで受けとったidのところのデータを更新する。
    // 引数で受け取ったtodoとidをプレースホルダーにセットする。
    $sql = 'UPDATE todos SET todo = :todoText WHERE id = :id';
    // sql文の実行準備、PDOStatementオブジェクトが返る。
    $stmt = $dbh->prepare($sql);
    // PDOStatementオブジェクトのbindvalueで:todoTextに$postされたtodo(入力された)をセット。
    $stmt->bindValue(':todoText', $post['todo'], PDO::PARAM_STR);
    // PDOStatementオブジェクトのbindvalueで:idに$postされたid(更新対象の)をセット。
    $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
    // SQLを実行する
    $stmt->execute();
    // 処理が終わったらfunctions.phpのsavePostedData関数へ戻る
}


// 削除する関数
function deleteTodoData($id)
{
    // DB接続。インスタンス化されたPDOクラスを$dbhへ代入。PDOクラスをインスタンス化したものがここに返る。
    $dbh = connectPdo();
    // 現在の時間を取得。
    $now = date('Y-m-d H:i:s');
    // 実行するsql文、PDOStatementオブジェクトが返る。
    $sql  = 'UPDATE todos SET deleted_at = :deleted_at WHERE id = :id';
    // sql文の実行準備、PDOStatementオブジェクトが返る。
    $stmt = $dbh->prepare($sql);
    // :deleted_atに&now(今の時間)をセット
    $stmt->bindValue(':deleted_at', $now, PDO::PARAM_STR);
    // idに$id(引数)をセットする
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    // sql文を実行
    $stmt->execute();
    // 処理が終わったらfunctions.phpのsavePostedData関数へ戻る
}


// 現在保存されているデータを取得する関数
function getTodoTextById($id)
{
    // DB接続。インスタンス化されたPDOクラスを$dbhへ代入。PDOクラスをインスタンス化したものがここに返る。
    $dbh = connectPdo();
    // 実行するsql文(対象のidの取得。論理削除されていないもの)
    $sql = 'SELECT * FROM todos WHERE id = :id AND deleted_at IS NULL';
    // sql文の実行準備、PDOStatementオブジェクトが返る
    $stmt = $dbh->prepare($sql);
    // :idに$id(引数)をセットする
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    // sql文の実行
    $stmt->execute();
    // PDOStatementのfetchメソッドで実行結果を取得
    $data = $stmt->fetch();
    // 実行結果のtodoカラムだけをreturn
    return $data['todo'];
}
