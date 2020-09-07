<?php

// DB接続ファイルを読み込む
require_once('connection.php');
// セッションをスタートする
session_start();


// SESSIONにハッシュ化したtokenを入れる
function setToken()
{
    // uniqid関数で一意なID(23文字)を生成して、sha1でハッシュ化。トークンに代入する。
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}


// SESSIONに格納されているtokenのチェックを行う。
function checkToken($token)
{
    // トークンが空またはトークンが格納されている値と違かったらSESSIONにエラー文を格納する
    // サーバー側とクライアント側のtokenの整合性を確認(newとeditからstore経由)。新規作成時と更新時にデータのやり取りがあったときに呼び出す
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}


// SESSIONに格納されているエラーメッセージを空にする
// ブラウザ上にエラーメッセージを表示させないようにする
function unsetError()
{
    $_SESSION['err'] = '';
}


// ページ遷移する
function redirectToPostedPage()
{
    // どこのページから訪問してきたか(現在のページのURL)
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    // そのまま現在のURLのままexitする
    exit();
}


// エスケープ処理
function escape($text)
{
    // フォームから送られてきた値や、データベースから取り出した値をブラウザ上に表示する。主に、悪意のあるコードの埋め込みを防ぐ目的で使う。
    // (エスケープする文字列, エスケープの種類, 文字コード)
    return htmlspecialchars($text, ENT_QUOTES, 'utf-8');
}


// getAllRecords関数を実行する
function getTodoList()
{
    // すべてのTODOを取得する
    return getAllRecords();
}


// getTodoTextById関数を実行する
function getSelectedTodo($id)
{
    // すでに保存されているTODOを取得する
    return getTodoTextById($id);
}


// リクエストもとで処理を分岐する
function savePostedData($post) // store.phpの$_POSTの中身
{
    // $postで受け取ったトークンのチェックを行う
    checkToken($post['token']);
    // バリデーションチェック
    validate($post);
    // URLを文字列として取得して$pathへ格納
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':  // リクエストもとがnewだったら
        createTodoData($post['todo']); // createTodoDataを実行(store.phpの$_POSTのtodoのみ取得)
        break;  // ループを抜けて再びstore.phpは遷移する
        case '/edit.php': // リクエストもとがeditだったら
        updateTodoData($post); // updateTodoDataを実行
        break; // ループを抜けて再びstore.phpは遷移する
        case '/index.php': // リクエストもとがindexだったら(一覧画面から削除したとき)
        deleteTodoData($post['id']); // hiddenで持たせた削除対象のdeleteTodoData関数を実行
        break; // ループを抜けて再びstore.phpは遷移する
        default:
        break;
    }
}


// 現在のURLを取得する
function getRefererPath()
{
    // 現在のURLを要素ごと(http/domain/portとか)に分解して連想配列にする
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    // path(/new.phpとか/edit.phpとかのルートドメイン以下のパスを返す)
    return $urlArray['path'];
}


// バリデーション
function validate($post)
{
    // todoフィールドに値がないor空文字だったら
    if (isset($post['todo']) && $post['todo'] === '') {
        $_SESSION['err'] = '入力がありません';
        // 現在のページのまま
        redirectToPostedPage();
    }
}
