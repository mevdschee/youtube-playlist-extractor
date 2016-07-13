<?php
$replaces = [['|GopherCon 2015: |',''],['|GopherCon 2014 |','']];
$list = 'PL2ntRZ1ySWBcD_BiJiDJUcyrb2w3bTulF'; // 2014
//$list = 'PL2ntRZ1ySWBf-_z-gHCOR2N156Nw930Hm'; // 2015
$base = 'https://www.youtube.com';
$url = $base.'/playlist?list='.$list;
$content = file_get_contents($url);
$count = preg_match_all('|<tr class="pl-video([^>]*)>(.*)</tr>|msiU',$content,$matches);
for($i=0;$i<$count;$i++) {
if (!preg_match('|<a([^>]*)>([^<]*)</a>|msi',$matches[0][$i],$link)) continue;
if (!preg_match('|href="(/watch[^&]*)&amp|i',$link[1],$href)) continue;
if (!preg_match('|<td class="pl-video-time"([^>]*)>(.*)</td>|msiU',$matches[0][$i],$time)) continue;
$href = $base.trim($href[1]);
$title = trim($link[2]);
$time = trim(strip_tags($time[2]));
foreach ($replaces as $replace) {
$title = preg_replace($replace[0],$replace[1],$title);
}
//echo "<a href=\"$href\">$title [$time]</a>\n";
echo html_entity_decode("- [$title [$time]]($href)\n");
}
