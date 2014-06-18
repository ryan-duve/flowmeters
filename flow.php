<?php
 $connect = mysql_connect("localhost","uva","uva1");
 mysql_select_db("slowcontrols",$connect);
 
 //make one query for all data in last 2 minute
 //http://stackoverflow.com/questions/4961524/mysql-query-latest-timestamp-unique-value-from-the-last-30-minutes
 $flow_query="SELECT device AS d, created_at AS createdtime, measurement_reading AS measurement FROM usb1208ls WHERE created_at > (now() - interval 120 second)";


 //not getting anything back?  try running this
// $test_query="SELECT device AS d, created_at AS createdtime, measurement_reading AS measurement FROM usb1208ls WHERE created_at > (now() - interval 120 second)";
// $test_result=mysql_query($test_query) or die(mysql_error());
// $test_r=mysql_fetch_assoc($test_result);
// echo "console.log('".$test_r['d']."');";


 //run one query
 $flow_result=mysql_query($flow_query) or die(mysql_error());

 //go through results, stacking four data arrays

 while($r=mysql_fetch_assoc($flow_result)){

	//factor of 1K for javascript time, subtract 18K seconds for flot EST->GMT fudge factor
 	$ctime=(strtotime($r["createdtime"])-18000)*1000;

	switch ($r["d"]){
		case "d0": 
			$data0[]="[\"".$ctime."\",\"".$r["measurement"]."\"]";
			break;
		case "d1": 
			$data1[]="[\"".$ctime."\",\"".$r["measurement"]."\"]";
			break;
		case "d2": 
			$data2[]="[\"".$ctime."\",\"".$r["measurement"]."\"]";
			break;
		case "d3": 
			$data3[]="[\"".$ctime."\",\"".$r["measurement"]."\"]";
			break;
	}
 }

 //echo "data0=".implode(',',$data0)."\n";

 //make output like:
 //[	{ label:"Foo", data: [ [10, 1], [17, -14] },
 // 	{ label:"Bar", data: [ [11, 13], [19, 11] }
 //]

 $res="{\"separator\":";
 $res.="{\"label\":\"Separator\",\"data\":[";
 $res.=implode(',',$data0);
 $res.="]},\"screen\":{\"label\":\"Screen\",\"data\":[";
 $res.=implode(',',$data1);
 $res.="]},\"he-3\":{\"label\":\"He-3\",\"data\":[";
 $res.=implode(',',$data2);
 $res.="]},\"evaporator\":{\"label\":\"Evaporator\",\"data\":[";
 $res.=implode(',',$data3);
 $res.="]}}";

 echo $res;

 //echo "console.log('leaving flow.php')";

?>
