<?php
 $connect = mysql_connect("localhost","uva","uva1");
 mysql_select_db("slowcontrols",$connect);
 

//http://stackoverflow.com/questions/4961524/mysql-query-latest-timestamp-unique-value-from-the-last-30-minutes
 $flow_query="SELECT device AS label, created_at AS createdtime, measurement_reading AS measurement FROM usb1208ls WHERE device='d1' AND created_at > (now() - interval 120 second)ORDER BY created_at DESC, id DESC";

 $flow_result=mysql_query($flow_query) or die(mysql_error());

 $res="{\"label\":\"d1\",\"data\":[";
 while($r=mysql_fetch_assoc($flow_result)){
	 $ctime=strtotime($r["createdtime"])*1000;
//	 $res.="[$r[id],$r[measurement]],";
	 $res.="[$ctime,$r[measurement]],";
 }
 $res=substr($res,0,-1);
 $res.="]}";

 echo $res;

?>
