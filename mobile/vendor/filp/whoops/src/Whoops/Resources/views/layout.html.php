<?php
//dezend by  QQ:2172298892
echo "<!DOCTYPE html>\r\n<html>\r\n  <head>\r\n    <meta charset=\"utf-8\">\r\n    <title>";
echo $tpl->escape($page_title);
echo "</title>\r\n\r\n    <style>";
echo $stylesheet;
echo "</style>\r\n  </head>\r\n  <body>\r\n\r\n    <div class=\"Whoops container\">\r\n\r\n      <div class=\"stack-container\">\r\n        <div class=\"frames-container cf ";
echo !$has_frames ? 'empty' : '';
echo "\">\r\n          ";
$tpl->render($frame_list);
echo "        </div>\r\n        <div class=\"details-container cf\">\r\n          <header>\r\n            ";
$tpl->render($header);
echo "          </header>\r\n          ";
$tpl->render($frame_code);
echo '          ';
$tpl->render($env_details);
echo "        </div>\r\n      </div>\r\n    </div>\r\n\r\n    <script src=\"//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/1.3.5/ZeroClipboard.min.js\"></script>\r\n    <script src=\"//cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.js\"></script>\r\n    <script>";
echo $zepto;
echo "</script>\r\n    <script>";
echo $javascript;
echo "</script>\r\n  </body>\r\n</html>\r\n";

?>
