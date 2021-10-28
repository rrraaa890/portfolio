<?php
require_once("env.php");

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$_SESSION = array();
session_start();


// 一回ログインしていればnameがあるのでリダイレクト
// header関数はhtmlより前に記述
if(!empty($_SESSION["name"])){
  header("location:/kanri.php");
  exit();
}


// postのバリデーション
if(!empty($email)){
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "入力された値が不正です。";
    return false;
  }
}

//DB内でPOSTされたメールアドレスを検索
try {
  $option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
  );
  $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
  $stmt = $pdo->prepare('select * from user where email = ?');
  $stmt->execute([$_POST['email']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

// emailがDB内に存在しているか確認
if(!empty($_POST["email"])){
  if (empty($row['email'])) {
    echo 'メールアドレスまたはパスワードが間違っています。';
    return false;
  }
}

//パスワード確認後sessionにメールアドレスを渡す
if(!empty($_POST["password"])){
  if (password_verify($_POST['password'], $row['password'])) {
    //session_idを新しく生成し、置き換える
    session_regenerate_id(true);
    //DBのユーザー情報をセッションに保存
    $_SESSION['name'] = $row['name'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['password'] = $row['password'];
    
    header('Location: /kanri.php');
  exit();
   } else {
    echo 'メールアドレス又はパスワードが間違っています。';
    return false;
  }
}



?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面</title>
  </head>
  <style>
      /* ======================
      reset.css
  ====================== */
  /*
html5doctor.com Reset Stylesheet
v1.6.1
Last Updated: 2010-09-17
Author: Richard Clark - http://richclarkdesign.com
Twitter: @rich_clark
*/

/*要素のフォントサイズやマージン・パディングをリセットしています*/
html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
abbr, address, cite, code,
del, dfn, em, img, ins, kbd, q, samp,
small, strong, sub, sup, var,
b, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure,
footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video {
    margin:0;
    padding:0;
    border:0;
    outline:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

/*行の高さをフォントサイズと同じにしています*/
body {
    line-height:1;
}

/*新規追加要素のデフォルトはすべてインライン要素になっているので、section要素などをブロック要素へ変更しています*/
article,aside,details,figcaption,figure,
footer,header,hgroup,menu,nav,section {
    display:block;
}

/*nav要素内ulのマーカー（行頭記号）を表示しないようにしています*/
/*nav ul {
    list-style:none;
}*/
ol, ul {
    list-style: none;
}

/*引用符の表示が出ないようにしています*/
blockquote, q {
    quotes:none;
}

/*blockquote要素、q要素の前後にコンテンツを追加しないように指定しています*/
blockquote:before, blockquote:after,
q:before, q:after {
    content:'';
    content:none;
}

/*a要素のフォントサイズなどをリセットしフォントの縦方向の揃え位置を親要素のベースラインに揃えるようにしています*/
a {
    margin:0;
    padding:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

/* ins要素のデフォルトをセットし、色を変える場合はここで変更できるようにしています */
ins {
    background-color:#ff9;
    color:#000;
    text-decoration:none;
}

/* mark要素のデフォルトをセットし、色やフォントスタイルを変える場合はここで変更できるようにしています
また、mark要素とは、文書内の検索結果で該当するフレーズをハイライトして、目立たせる際に使用するようです。*/
mark {
    background-color:#ff9;
    color:#000;
    font-style:italic;
    font-weight:bold;
}

/*テキストに打ち消し線が付くようにしています*/
del {
    text-decoration: line-through;
}

/*IEではデフォルトで点線を下線表示する設定ではないので、下線がつくようにしています
また、マウスオーバー時にヘルプカーソルの表示が出るようにしています*/
abbr[title], dfn[title] {
    border-bottom:1px dotted;
    cursor:help;
}

/*隣接するセルのボーダーを重ねて表示し、間隔を0に指定しています*/
table {
    border-collapse:collapse;
    border-spacing:0;
}

/*水平罫線のデフォルトである立体的な罫線を見えなくしています*/
hr {
    display:block;
    height:1px;
    border:0;  
    border-top:1px solid #cccccc;
    margin:1em 0;
    padding:0;
}

/*縦方向の揃え位置を中央揃えに指定しています*/
input, select {
    vertical-align:middle;
}

/*画像を縦に並べた時に余白が出ないように*/
img {
    vertical-align: top;
    font-size: 0;
    line-height: 0;
}

/*box-sizingを全ブラウザに対応*/
*, *:before, *:after {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -o-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
}

li{
  list-style: none;
}
a{
  text-decoration: none;
  color: #000;
}
  /* ======================
      style.css
  ====================== */

  body{
    width: 80%;
    margin: 0 auto;
  }
  h1{
    padding-top: 80px;
    text-align: center;
    font-size: 50px;
  }
    /* ーーーーーーーログインフォームーーーーー */
  .login{
    display: flex;
    flex-wrap: wrap;
    width: 300px;
    margin:0 auto;
  }
  input{
    width: 300px;
    height: 40px;
    border-radius: 3px;
  }
  .login_submit{
    width: 100px;
    margin: 10px 100px;
  }
  </style>
<body>
  <h1>ログイン</h1>
  <div class="login">
    <form action="" method="post">
      <label for="name">name:</label>
      <input type="text" name="name" id="name">
      <label for="email">email:</label>
      <input type="email" name="email" id="email">
      <label for="password">password:</label>
      <input type="password" name="password" id="password">
      <input type="submit" class="login_submit" value="ログイン">
    </form>
  </div> 
</body>
</html>