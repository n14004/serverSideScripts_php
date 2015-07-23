<?php
require '../lib/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register(); // Twigを使う時のおまじない
$loader = new Twig_Loader_Filesystem('./templates'); // Twigで使用するテンプレートファイルを格納する場所
// Twigの設定
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache'
));

//データベースの設定
$host = 'localhost';
$dbname = 'kadai';
$charset = 'utf8';
$user = 'n14004';
$password = '';
$driver = 'mysql';
$connection = sprintf("%s:host=%s;dbname=%s;charset=%s",$driver,$host,$dbname,$charset);
$dbh = new PDO($connection,$user,$password);
// エラーが起きたら例外を投げる…
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$now = date('Y-m-d H:i:s'); //投稿時間を取得


$query = 'INSERT INTO students(id,Name,class,create_date,score)
 VALUES (:id,:Name,:class,:create_date,:score)';
// SQLが実行可能な状態にしておく
$stmt = $dbh->prepare($query);

$stmt->bindParam(':id', $_POST['id'],PDO::PARAM_STR);
$stmt->bindParam(':Name', $_POST['name'],PDO::PARAM_STR);
$stmt->bindParam(':class', $_POST['class'],PDO::PARAM_STR);
$stmt->bindParam(':create_date',$now ,PDO::PARAM_STR);
$stmt->bindParam(':score', $_POST['score'],PDO::PARAM_INT);

// 実 行
$stmt->execute();

// あとはTwig使って表示するだけ。
print($twig->render('done.tpl',
  array('id'=>$_POST['id'],
  	'name'=>$_POST['name'],
  	'class'=>$_POST['class'],
  	'create_date'=>$now, //時間表示
  	'score'=>$_POST['score'])));

// データベースの接続を終了
unset($dbh);
