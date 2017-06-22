<?php
ini_set( 'display_errors', 1 ); 
require_once('phpQuery-onefile.php');

// htmlを取得
$html = file_get_contents("https://weather.yahoo.co.jp/weather/jp/13/4410.html");
 
// phpQueryのドキュメントオブジェクトを生成
$doc = phpQuery::newDocument($html);

// 日付用の配列
$array_date = array();

// テーブル一行目を取得
for($i = 1 ; $i < 6; $i++){
    $array_date[] = $doc[".yjw_table tr:eq(0) td:eq(".$i.")"]->text();
}

// 最高気温用の配列
$array_temp_high = array();

// 最高気温を取得
for($i = 1 ; $i < 6; $i++){
    $array_temp_high[] = $doc[".yjw_table tr:eq(2) td:eq(".$i.") font:eq(0)"]->text();
}

// 最低気温用の配列
$array_temp_low = array();

// 最低気温を取得
for($i = 1 ; $i < 6; $i++){
    $array_temp_low[] = $doc[".yjw_table tr:eq(2) td:eq(".$i.") font:eq(1)"]->text();
}

// スクレイピングしたデータをJSON化
for($i = 1 ; $i < 6; $i++){
    // 日付を取得
    $date = $doc[".yjw_table tr:eq(0) td:eq(".$i.")"]->text();
    // 最高気温を取得
    $temp_high = $doc[".yjw_table tr:eq(2) td:eq(".$i.") font:eq(0)"]->text();
    // 最低気温を取得
    $temp_low = $doc[".yjw_table tr:eq(2) td:eq(".$i.") font:eq(1)"]->text();

    $jsonData[] = ['date' => $date, 'temp_high' => $temp_high, 'temp_low' => $temp_low];
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>テストページ</title>
		<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	</head>
	<body>
		<h1>一週間の温度</h1>
		<div id="LineChart" style="height: 300px;width: 600px;">

			<script language="JavaScript">
				new Morris.Line({
					element: 'LineChart', 
					data:<?php echo json_encode($jsonData, JSON_UNESCAPED_UNICODE); ?>,  // 加工したjsonデータ
					xkey: 'date',              			// x軸
					ykeys: ['temp_high', 'temp_low'],   // y軸
					postUnits: '度',                
					labels: ['最高気温', '最低気温'],            
					barColors: ['#c82f2f', '#605ca8'],
					parseTime: false
				});
			</script>
		</div>
	</body>
</html>
