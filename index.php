<?php
$requestUri = preg_replace('/\?.+/', '', $_SERVER['REQUEST_URI']);

// setting.jsonの読み込み
$settingJson = file_get_contents(__DIR__.'/setting.json');
$settingArray = json_decode($settingJson, true);

// エントリポイント一覧の取得
$entryPoints = array_column($settingArray, 'entryPoint');

// REQUEST_URIのエントリポイントがヒットするか確認
$entryPointIndex = array_search($requestUri, $entryPoints);

header("Access-Control-Allow-Origin: *");

// ルートにアクセスがあった場合、setting.jsonをそのまま返す
if ($requestUri === '/') {
    echo $settingJson;
    return;
}

// 登録外のエントリポイントの場合、404を返す
if ($entryPointIndex === false) {
    header('HTTP/1.1 404');
    return;
}

sleep($settingArray[$entryPointIndex]['sleep']);

// レスポンス生成
header($settingArray[$entryPointIndex]['contentType']);
header('HTTP/1.1 '.$settingArray[$entryPointIndex]['httpStatusCode']);

if (strpos($settingArray[$entryPointIndex]['contentType'], 'xml') !== false) {
    // レスポンスがXMLの場合
    echo $settingArray[$entryPointIndex]['response'];
} else if (strpos($settingArray[$entryPointIndex]['contentType'], 'json') !== false) {
    // レスポンスがjsonの場合
    echo json_encode($settingArray[$entryPointIndex]['response']);
}
