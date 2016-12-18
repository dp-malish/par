<?php 
/*function Parse($p1, $p2, $p3) {
$num1 = strpos($p1, $p2);
if ($num1 === false) return 0;
$num2 = substr($p1, $num1);
return strip_tags(substr($num2, 0, strpos($num2, $p3)));
}

function Parse_teg($p1, $p2, $p3) {
    $num1 = strpos($p1, $p2);
    if ($num1 === false) return 0;
    $num2 = substr($p1, $num1);
    return substr($num2, 0, strpos($num2, $p3));
}

$String = file_get_contents('http://timeweb.com/ru/');
echo Parse($String, '<div class="menu">', '</div>');

echo '<br>'.Parse_teg($String, '<div class="menu">', '</div>').'<br>'.'<br>';


echo '<br>'.Parse($String, '<title>', '</title>');*/
//****************************************************
//print_r (curl_version());
$site=curl_init('http://dp-malish.com');

curl_setopt($site,CURLOPT_RETURNTRANSFER,true);
curl_setopt($site,CURLOPT_HEADER,true);
//curl_setopt($site,CURLOPT_NOBODY,true);
curl_setopt($site,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($site,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($site,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($site,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36 OPR/42.0.2393.85');

$html=curl_exec($site);

curl_close($site);

echo $html;