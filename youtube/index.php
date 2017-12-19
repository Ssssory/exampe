<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Сохранение превью с youtube</title>

</head>
<body>
<?
if (empty($_POST['id'])){
    $videoLink = '';
}else{
    $videoLink = $_POST['id'];
}
if (empty($_POST['format'])){
    $ingFormat = '';
}else{
    $ingFormat = $_POST['format'];
}
// https://www.youtube.com/watch?v=HFyiZpI-ToQ
?>
<form method="post">
    <label for="id">Идентификатор видео </label>
    <input type="text" name="id" value="" placeholder="<?=$videoLink?>">
    <label for="format"></label>
    <select name="format" id="format">
        <option value="maxresdefault">maxresdefault</option>
        <option value="sddefault">sddefault</option>
        <option value="hqdefault">hqdefault</option>
        <option value="mqdefault">mqdefault</option>
        <option value="default">default</option>
        <option value="3">3</option>
        <option value="2">2</option>
        <option value="1">1</option>
        <option value="0">0</option>
        <option value="all">all</option>
    </select>
    <input type="submit">
</form>

<?
if (!empty($videoLink)){

    $videoID = $videoLink;
    // если передана вся ссылка
    if (strlen($videoLink) > 20){
        $videoID = explode('=',$videoLink)[1];
    }
//    echo $videoID;
    echo '<br><br>';
    if ($ingFormat == '') {
        $format = 'mqdefault';
    }else{
        $format = $ingFormat;
    }
    if ($format == 'all'){
        $arResult = getAddr($videoID,$format);
        krsort($arResult);
        foreach ($arResult as $item) {
            echo '<img src="'. $item .'">' . '<br>';
        }
    }else{
        echo '<img src="'.getAddr($videoID,$format).'">';
    }


}

/**
 * @param $videoID
 * @param $format
 * @return string
 */
function getAddr($videoID, $format){
    $imgStart = 'https://img.youtube.com/vi/';
    $arFormat = array(
        'maxresdefault' => 'maxresdefault',
        'sddefault'     => 'sddefault',
        'hqdefault'     => 'hqdefault',
        'mqdefault'     => 'mqdefault',
        'default'       => 'default',
        3               => 3,
        2               => 2,
        1               => 1,
        0               => 0
    );
    if ($format != 'all'){
        $imgSrc = $imgStart . $videoID .'/'. $arFormat[$format] . '.jpg';
    }else{
        $arResult = array();
        foreach ($arFormat as $key => $value){
            $arResult[] = $imgStart . $videoID .'/'. $arFormat[$key] . '.jpg';
        }
        return $arResult;
    }
    return $imgSrc;
}
?>
</body>
</html>