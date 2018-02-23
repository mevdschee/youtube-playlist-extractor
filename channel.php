<?php
$replaces = [['|   |',' - '],['| - PyCon 2016|',''],['|&#39;|','\'']];
$format = 'markdown'; // markdown, json, html
$channel = 'UCwTD5zJbsQGJN75MwbykYNw'; // PyCon 2016 
$base = 'https://www.youtube.com';

// retrieve
$url = $base.'/channel/'.$channel.'/videos?live_view=500&flow=list&sort=dd&view=0';
$matches = false;
$next = $content = ''; 
do {
  if ($matches) {
    $obj = json_decode(file_get_contents($base.$matches[1]));
    $next = $obj->content_html;
    $next .= $obj->load_more_widget_html;
  }
  else $next = file_get_contents($url);
  if ($next) $content .= $next;
  else break;
} while (preg_match('|data-uix-load-more-href="([^"]*)"|msiU',$next,$matches));

preg_match_all('|<span class="video-time([^>]*)>(.*)</span>|msiU',$content,$times);
$count = preg_match_all('|<h3 class="yt-lockup-title ([^>]*)>(.*)</h3>|msiU',$content,$matches);
$videos = [];
for($i=0;$i<$count;$i++) {

  if (!preg_match('|<a([^>]*)>([^<]*)</a>|msi',$matches[0][$i],$link)) continue;
  if (!preg_match('|href="(/watch[^"]*)"|i',$link[1],$href)) continue;

  $href = $base.trim($href[1]);
  $title = trim($link[2]);
  $time = trim(strip_tags($times[2][$i]));
  foreach ($replaces as $replace) {
    $title = preg_replace($replace[0],$replace[1],$title);
  }
  $videos[] = (object) compact('href','title','time');
}

// print
echo '<pre>';
if ($format=='json') {
  echo json_encode($videos);
} else if ($format=='html') {
  foreach ($videos as $video) {
    extract((array)$video);
    echo "<a href=\"$href\">$title</a> [$time]\n";
  }
} else if ($format=='markdown') {
  foreach ($videos as $video) {
    extract((array)$video);
    echo html_entity_decode("- [$title]($href) [$time]\n");
  }
}
