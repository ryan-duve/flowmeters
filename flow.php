<?php
 $connect = mysql_connect("localhost","uva","uva1");
 mysql_select_db("slowcontrols",$connect);
 
 for($i=0;$i<4;$i++){
	//http://stackoverflow.com/questions/4961524/mysql-query-latest-timestamp-unique-value-from-the-last-30-minutes
	$flow_query[]="SELECT device AS label, created_at AS createdtime, measurement_reading AS measurement FROM usb1208ls WHERE device='d$i' AND created_at > (now() - interval 120 second)ORDER BY created_at DESC, id DESC";

 }

 for($i=0;$i<4;$i++){
	$flow_result[]=mysql_query($flow_query[$i]) or die(mysql_error());

 }

 $res="[";
 for($i=0;$i<2;$i++){
 	$res.="{\"label\":\"d$i\",\"data\":[";
 	while($r=mysql_fetch_assoc($flow_result[$i])){
 	        $ctime=strtotime($r["createdtime"])*1000;
 	        $res.="[$ctime,$r[measurement]],";
 	}
 	$res=substr($res,0,-1);
 	$res.="]},";
 }
 $res=substr($res,0,-1);
 $res.="]";

 echo $res;

?>
