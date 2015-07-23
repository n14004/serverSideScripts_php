<?php
require '../lib/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register(); // Twigを使う時のおまじない
$loader = new Twig_Loader_Filesystem('./templates'); // Twigで使用するテンプレートファイルを格納する場所

// Twigの設定
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache'
));

// データベースの設定
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


// ここからがadd.phpと違うところ。データ呼び出してみるよ!
$query = 'SELECT id,Name,class,create_date,score FROM students WHERE id = :id';
$stmt = $dbh->prepare($query);
$id = $_GET['id'];
//$stmt->bindValue(":id",1,PDO::PARAM_INT); // id(ページID)が1のものを表示してます。
$stmt->bindValue(":id",$id,PDO::PARAM_STR); 
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_OBJ);


//print_r($data); // デバッグコード

// Twigにはめこむ
print($twig->render('output.tpl',
  array('id'=>$data->id,
  	'name'=> $data->Name,
  	'class'=> $data->class,
  	'create_date' => $data->create_date,
  	'score'=> $data->score)));


// データベースの接続を終了する
unset($dbh);
