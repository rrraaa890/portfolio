<?php
// 変数の初期化

$err=array();
session_start();

$name=$_SESSION["name"];
$email=$_SESSION["email"];
$message=$_SESSION["message"];

if(!empty($_POST["submit"])){
  // <!-- 送信する -->
  $message = "お問い合わせを受け付けました\r\n"
  . "名前:" . $_SESSION["name"] . "\r\n"
  . "email:" . $_SESSION["email"] . "\r\n"
  . "お問い合わせ内容:\r\n"
  . preg_replace("/\r\n|\r|\n/","\r\n", $_SESSION["message"] );
  mail($_SESSION["email"], "お問い合わせありがとうございます", $message );
  mail("ryu890b@yahoo.co.jp", "messageを受け付けました", $message );
  $_SESSION = array();
  header('Location: /complete.php');
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ポートフォリオ</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
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
    list-style: none; 
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
    color: #000;
    text-decoration: none;
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
  /* ======================
      style.css
  ====================== */

body{
  width: 100%;
  margin:0 auto;
  padding: 100px;
}
.contact{
  margin: 20px;
}
h2{
  margin: 30px 0;
  font-size: 20px;
}
.submit{
  display: flex;
}
a{
  margin: 5px 50px;
}

</style>
<body>
  <h1>内容の確認</h1>
  <div class="contact">
    <h2>name</h2>
    <?php echo $name; ?>
    <h2>email</h2>
    <?php echo $email; ?>
    <h2>message</h2>
    <?php echo $message; ?>
  </div>
  <div class="submit">
    <form action="" method="post">
      <input type="submit" value="送信" name="submit">
    </form>
    <a href="/index.php">戻る</a>
  </div>
</body>
</html>