<?php
// PHP访客计数器
function num()
{
if(!file_exists("n.txt"))
{
$fp=fopen("n.txt","w");
fwrite($fp,"1");
fclose($fp);
$n=1;
}
else
{
$fp=fopen("n.txt","r");
$n=fgets($fp);
fclose($fp);
$n++;
$fp=fopen("n.txt","w");
fwrite($fp,$n);
fclose($fp);
}
return $n;
}
?>
<!-- 本程序使用时需要上传至相应目录，然后在显示访客人数的页面加入以下代码：
require_once"count.php";
$guestsnum=num();
echo"您是第".$guestsnum."位访客"; -->
 