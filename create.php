<?php

require_once("env.php");

// POSTで受け取った値を格納
$title = $_POST["title"];
$news = $_POST["news"];
$time  = $_POST["time"];
$err = null;
session_start();

// logoutを押したら$_SESSIONにあるnameとpasswordを消してindex.phpに飛ぶ
if( !empty($_GET['logout']) ) {
	unset($_SESSION["password"],$_SESSION["name"]);
  header('Location: /index.php');
  exit();
}

// $_SESSIONにnameがあるかでこのページを表示するかloginページに飛ばすか
if(empty($_SESSION["name"])){
  header("location:/login.php");
  exit();
}

if(!empty($_POST["create"]) && !empty($_POST["title"]) && !empty($_POST["news"])){
  // 書き込み日時を取得
  $time = date("Y-m-d H:i:s");
  $news = nl2br($news, false);
  $news = preg_replace( '<br>', '/\n/', $_POST['news']);
  try {
    ini_set("display_errors",true);
  
    //データベースに接続
    $option = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
        );
    $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
  
    //トランザクション開始
    $pdo->beginTransaction();
  
    //データSQLを準備
    $stmt = $pdo->prepare('INSERT INTO news (title,news,time) VALUES (:title, :news, :time)');
  
    //値をセット
  
    $stmt->bindParam( ':title', $title, PDO::PARAM_STR);
    $stmt->bindParam( ':news', $news, PDO::PARAM_STR);
    $stmt->bindParam( ':time', $time, PDO::PARAM_STR);

   
  
    //SQL実行
    $res = $stmt->execute();
  
    //コミット
    if( $res ) {
      $pdo->commit();
      $err = "登録完了しました。";
    }
    
    
  } catch(PDOException $e) {
    
    //エラーメッセージを出力
    echo $e->getMessage();
    echo "touroku";
    
    //ロールバック
    $pdo->rollBack();
    
  } finally {
    
    //データベースの接続解除
    $pdo = null;
  }
}else{
    $err = "titleまたはnewsを入力してください";
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
  .wrap{
    display: flex;
    justify-content: space-between;
  }

  h1{
    padding-top: 80px;
    text-align: center;
    font-size: 50px;
  }
  li{
    padding: 15px;
    font-size: 30px;
  }

  /* .center{
    display: flex;
    flex-direction: column;
  } */
  .center{
    display: flex;
    flex-direction: column;
    width: 55%;
  }
  label{
    margin: 20px;
    font-weight: bold;
  }
  .center input{
    height: 35px;
    font-size: 20px;
  }
  .center textarea{
    height: 400px;
    font-size: 20px;
  }
  .submit{
    width: 100px;
    margin: 15px 0;
  }
  .err{
    color: red;
    width: 50%;
    font-weight: bold;
  }
  .logout{
    width: 120px;
    font-weight: 900;
    width: 150px;
    height: 40px;
    border-radius: 3px;
    margin: 15px;
  }
 

  </style>
<body>
  <h1>管理画面</h1>
  <div class="wrap">
    <div class="left">
      <ul>
        <li><a href="kanri.php">記事一覧</a></li>
        <li><a href="">記事投稿</a></li>
        <li><a href="signup.php">新規登録</a></li>
      </ul>
    </div>
      <form action="" method="post" class="center">
        <label for="title">TITLE</label>
        <input type="text" name="title" id="title">
        <label for="news">NEWS</label>
        <textarea name="news" id="news" cols="30" rows="10"></textarea>
        <input type="hidden" name="time" >
        <input type="submit" value="投稿" class="submit" name="create">
        <div class="err">
          <?php 
          if(!empty($err)){
          echo $err;
          }?>
        </div>
      </form>
   
    <div class="right">
      <form action="">
        <input type="submit" name="logout" value="ログアウト" class="logout">
      </form>
    </div>
  </div>  

</body>
</html>