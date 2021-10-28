<?php
require_once("env.php");
// 変数の初期化
$_SESSION =null;
$err=array();
session_start();

if(!empty($_POST["submit"])){
  // 送信ボタン押されたら入力あるか確認
  if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["message"])){
    //session_idを新しく生成し、置き換える
    session_regenerate_id(true); 
    // サニタイズしてsessionに格納
    $_SESSION["name"]=htmlspecialchars($_POST["name"],ENT_QUOTES,"UTF-8");
    $_SESSION["email"]=htmlspecialchars($_POST["email"],ENT_QUOTES,"UTF-8");
    $_SESSION["message"]=htmlspecialchars($_POST["message"],ENT_QUOTES,"UTF-8");

    header('Location: /mailcheck.php');
    exit();
  }else{
    $err="正しく入力してください";
  }
}

// newsがあれば表示
// データベースに接続
try {

  $option = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
  );
  $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);

} catch(PDOException $e) {

// 接続エラーのときエラー内容を取得する
$error_message[] = $e->getMessage();
}

if( !empty($pdo) ) {

	// メッセージのデータを取得する
	$sql = "SELECT * FROM news ORDER BY time DESC";
	$news = $pdo->query($sql);
}
 // データベースの接続を閉じる
 $pdo = null;



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
  /* ======================
      style.css
  ====================== */

body{
  width: 100%;
  margin:0 auto;
  padding: 0;
}
li{
  list-style: none;
}
a{
  color: #000;
  text-decoration: none;
}
.wrap{
  width: 1000px;
  margin: 0 auto;
  padding: 0;
}
header{
  width: 100%;
  height: 100px;
  padding: 130px;
  margin: 0 ;
  display: flex;
  justify-content: space-between;
}
header h1{
  font-size: 25px;
  font-weight: normal;
  padding: 0;
  margin: 0;
}
.header-right ul{
  display: flex;
}
.header-right li{
  margin: 0 10px;
  font-weight: normal;
}

.main img{
  width: 100%;
  background-size: cover;
  height: 420px;
  object-fit: cover;
}
.about h1{
  text-align: center;
  padding: 50px;
  font-size: 30px;
}
.about h2{
  margin-bottom: 80px;
  font-weight: normal;
  font-size: 20px;
}
.about li{
  margin: 15px 0;
}
.about p{
  margin-top: 50px;
  line-height: 25px;
}

.works h1{
  text-align: center;
  padding: 50px;
  font-size: 30px;
}
.image-group{
  display: flex; 
  justify-content: space-between;
  flex-wrap: wrap;
}
.works img{
  width: 300px;
  height: 200px;
  margin: 15px;
}


.newss{
  width: 800px;
  height: 600px;
  overflow-y: scroll;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
}
.newss h1{
  text-align: center;
  padding: 50px;
  font-size: 30px;
}
.info{
  display: flex;
  flex-direction: column;
  background-color: #cccccc;
  margin-top: 30px;
  border: 1px solid #000;
  border-radius: 10px;
  padding: 10px;
  width: 800px;
}
.top{
  display: flex;
  justify-content: center;
}
time{
  margin: 0 auto;
}
.news{
  white-space: pre-wrap;
}


.contact h1{
  text-align: center;
  padding: 50px;
  font-size: 30px;
}
.err{
  border: 2px solid red;
  padding: 10px;
  border-radius: 3px;
  color: red;
}
input{
  display: block;
  border-radius: 8px;
  border: 1px gray solid;
  width: 100%;
  height: 40px;
  margin: 0 auto;
}
textarea{
  display: block;
  border-radius: 8px;
  border: 1px gray solid;
  width: 100%;
  margin: 0 auto;
}
.submit{
  width: 20%;
  height: 50px;
  background-color:dimgray;
  border-radius: 3px;
  margin: 10px auto;
}

footer{
  background-color: #003333;
  color: white;
  height: 200px;
}
footer ul{
  float: right;
}
footer li{
  margin: 10px;
}
footer a{
  color:white;
}


</style>
<body>
  <div class="wrap">
    <header>
      <h1>My Works</h1>
      <div class="header-right">
        <ul>
          <li><a href="#about">About</a></li>
          <li><a href="#works">Works</a></li>
          <li><a href="#news">News</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="https://www.instagram.com/instagram/?hl=ja"><i class="fab fa-instagram"></i></a></li>
        </ul>
      </div>
    </header>

  </div>
    <div class="main"><img src="img/IMG_0096.jpg" alt=""></div>
  <div class="wrap">
    <div class="about" id="about">
     <h1>About</h1>
     <h2>ryu hobby</h2>
     <ul>
       <li>HTML/CSS/JavaScript/php/python</li>
       <li>Camp</li>
       <li>Camera</li>
     </ul>
     <p>プログラミングは独学で勉強中です。このサイトはphpでフォームを作りました。<br>Cameraは趣味で結婚式の前撮りやキャンプで写真を撮って楽しんでいます。<br>Campはsnowpeakやガレージブランド愛用中です。自作で溶接したり木工したりとDIYして楽しんでます✌️</p>
    </div>
    <div class="works" id="works">
      <h1>Works</h1>
      <div class="image-group">
        <img src="img/IMG_0830.jpg" alt="">
        <img src="img/IMG_0199.JPG" alt="">
        <img src="img/IMG_0222.PNG" alt="">
        <img src="img/IMG_0929.JPG" alt="">
        <img src="img/IMG_2496 3.JPG" alt="">
        <img src="img/IMG_2556 3.JPG" alt="">
      </div>
    </div>
    
    <div class="newss" id="news">
    <h1>News</h1>
    <?php if( !empty($news) ){ ?>
      <?php foreach( $news as $report ){ ?>
        <article>
          <div class="info">
            <div class="top">
              <h2 class="title"><?php echo htmlspecialchars( $report['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
              <time><?php echo date('Y年m月d日 H:i', strtotime($report['time'])); ?></time>
            </div>
            <div class="bottom">
              <h2 class="news"><?php echo htmlspecialchars( $report['news'], ENT_QUOTES, 'UTF-8'); ?></h2>
            </div>
          </div>
        </article>
      <?php } ?>
    <?php } ?>

    </div>
    <div class="contact" id="contact">
      <h1>Contact</h1>
      <?php
    if( !empty($err)){
      echo '<div class="err">';
      echo  $err;
      echo '</div>';
    }
    ?>
      <form action="" method="POST">
        <label for="name">name:</label>
        <input type="name" name="name">
        <label for="email">email</label>
        <input type="email" name="email">
        <label for="message">message:</label>
        <textarea type="text" name="message"></textarea>
        <input type="submit" value="送信" class="submit" name="submit">
      </form>
    </div>
  </div>
  <footer>
    <div class="wrap">
      <div class="footer-right">
        <ul>
          <li><a href="#about">About</a></li>
          <li><a href="#works">Works</a></li>
          <li><a href="#news">News</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="https://www.instagram.com/instagram/?hl=ja"><i class="fab fa-instagram"></i></a></li>
          <li><a href="/login.php">管理者ログイン</a></li>
        </ul>
      </div>

    </div>
  </footer>
</body>
</html>