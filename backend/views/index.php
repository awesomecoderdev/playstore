<?php

$content = wp_remote_get("https://play.google.com/store/apps/details?id=com.sybogames.brim");
$content = wp_remote_retrieve_body($content);
$output = [];
$htmlDom = new DOMDocument();
@$htmlDom->loadHTML($content);
$name = $htmlDom->getElementsByTagName('h1')->item(0);
$name = (isset($name->textContent) && is_string($name->textContent)) ? trim($name->textContent) : "Unknown";
echo "Name: $name<br>";

$icon = $htmlDom->getElementsByTagName('img')->item(0);
$icon = $icon->getAttribute('src') ? $icon->getAttribute('src') : "unknown";
echo "Icon: $icon<br>";
echo "<img src='$icon' /><br>";

$rating = "";
$ratings =  $htmlDom->getElementsByTagName('div');
foreach ($ratings as $key => $r) {
    if ($r->getAttribute("itemprop") && $r->getAttribute("itemprop") == "starRating") {
        $r = (isset($r->textContent) && is_string($r->textContent)) ? trim($r->textContent) : "";
        $rating = str_replace("star", "", $r);
    }
}
echo "Rating: $rating<br>";
$downloaded = "";
$downloads =  $htmlDom->getElementsByTagName('div');
foreach ($downloads as $key => $d) {
    if ($d->childElementCount == 2) {
        $text = (isset($d->nodeValue) && is_string($d->nodeValue)) ? trim($d->nodeValue) : "Unknown";
        $down = strstr($text, 'Downloads', true);
        $load = strstr($down, 'reviews');
        if (strpos($load, "reviews") !== false) {
            $downloaded = str_replace("reviews", "", $load);
            break;
        }
    }
}
echo "Downloaded: $downloaded<br>";

$stared = "";
$satars =  $htmlDom->getElementsByTagName('div');
foreach ($satars as $key => $d) {
    if ($d->childElementCount == 2) {
        $text = (isset($d->nodeValue) && is_string($d->nodeValue)) ? trim($d->nodeValue) : "Unknown";
        $st = strstr($text, 'Downloads', true);
        $ar = strstr($st, 'star');
        $ed = strstr($ar, 'reviews', true);
        if (strpos($ed, "star") !== false) {
            $stared = str_replace("star", "", $ed);
            break;
        }
    }
}
echo "Stared: $stared<br>";


$developer = "";
$devName = "";
$devLink = "";
$links =  $htmlDom->getElementsByTagName('a');
foreach ($links as $key => $link) {
    if ($link->getAttribute("href")) {
        if (strpos($link->getAttribute("href"), "/store/apps/dev") !== false) {
            $devName = $link->nodeValue;
            $devLink = "https://play.google.com" . $link->getAttribute("href");
            break;
        }
    }
}

echo "<a href='$devLink'>$devName<a/>";





// foreach ($icon as $key => $i) {
//     if ($i->getAttribute('src')) {
//         echo '<pre>';
//         print_r($i->getAttribute('src'));
//         echo '</pre>';
//     }
//     // echo '<pre>';
//     // print_r($i->attributes);
//     // echo '</pre>';
// }
// echo $icon = isset($icon->getAttribute("src")) ? $icon->getAttribute('src') : "Unknown";



// foreach ($span as $key => $text) {
//     echo '<pre>';
//     print_r($text);
//     echo '</pre>';
//     echo is_string($text->textContent) ? trim($text->textContent) : $text->textContent;
// }

// echo '<pre>';
// print_r($span);
// echo '</pre>';

// foreach ($tables as $tableKey => $table) {
//     foreach ($table->getElementsByTagName('tr') as $trKey => $tr) {
//         $trData = [];
//         foreach ($table->getElementsByTagName('td') as $tdKey => $td) {
//             $key = $tdKey % 2 == 0 ? (is_string($td->textContent) ? trim($td->textContent) : $td->textContent) : null;
//             if ($key != null && !empty($key) && $key != " ") {
//                 $trData["keys"][] = is_string($td->textContent) ? trim($td->textContent) : $td->textContent;
//             }
//             $value = $tdKey % 2 != 0 ? (is_string($td->textContent) ? trim($td->textContent) : $td->textContent) : null;
//             if ($value != null && !empty($value) && $value != " ") {
//                 $trData["values"][] = is_string($td->textContent) ? trim($td->textContent) : $td->textContent;
//             }
//         }

//         $output["$tableKey"] = array_combine($trData["keys"], $trData["values"]);
//     }
// }
