<!DOCTYPE html>
<html data-embedded="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
  <title>幸福人生（优享版）利益测算表</title>

</head>

<body>

<style> 
body, html{
	height: 100%;
	width: 100%;
	margin:0;padding: 0;
}
h {text-align: center;
	font-weight: normal;
}
h2 {text-align: center;
	font-weight: normal;
	font-size: 16;
}
h3 {text-align: center;
	font-weight: normal;
	font-size: 14;
}
p {text-align: left;
font-size: 16;}
table {
	
	width: 80%;        
	border:1px solid #555;       
	border-width:1px 0 0 1px;       
	margin:2px auto;       
	text-align:center;        
	border-collapse:collapse; 
	}  
	td,th {        
	padding:3px;       
	font-size:12px;        
	border:1px solid #555;       
	border-width:0 1px 1px 0;       
	margin:2px 0 2px 0;
	vertical-align: middle;       
	text-align:center; } 
	th {        
	text-align:center;       
	font-weight:600;        
	background-color:#F4F4F4; 
	} 
	tr.alt td 
	{
	background-color:#EEF9FF;
	} 
</style>
<?php
//print vat_dump($—POST);
$m_age = $_POST['entry']['field_12'];
$m_baof = 10000 * $_POST['entry']['field_13'];
$m_sex = $_POST['entry']['field_14'];
$m_ppp = $_POST['entry']['field_9'];
$m_hongli = $_POST['entry']['field_4'];
$m_yanshi = $_POST['entry']['field_3'];

$m_input1 = $m_ppp.$m_sex.$m_age;

if ($m_sex == 'M') {
	$m_sex_c = '男' ;}
	else {
		$m_sex_c = '女' ;}

$unit = 1000;  //单位保额
$beishu = $m_baoe*10;
$baoxian_time = 105;  //保险期间


$ad_r_l=0;	//年度红利
$ad_r_m=0.0159;
$ad_r_h=0.0269;
//$td_r_l=0.0030;	//终了红利
//$td_r_m=0.0050;
//$td_r_h=0.0070;

//$total_r=0.030;

if ($m_hongli=='h') {
	$this_ad_r =$ad_r_h;
	$this_ad_c ='高档';
}
	else {
		if ($m_hongli=='m') {
			$this_ad_r =$ad_r_m;
			$this_ad_c ='中档';
		}
		else { $this_ad_r ==$ad_r_l;
			$this_ad_c ='低档';}
	}

if ($m_yanshi=='all') {
	$yanshi=0;
}
else {
	$yanshi=1;
}


$this_ad=0;		//年度红利保额
$this_ad_t=0;	//累积红利保额
$zhong_td=0;	//终了红利保额
$shen_gu=0;		//身故保险金


?>



<?php

if ($m_age>55  and $m_ppp=='10') {
echo '
<header class="clearfix">
        <div class="center user-info">
          
            <center><font color="yellow">被保险人年龄超过55周岁时，不能选择10年交，请重新输入!</font></center>
          
         
        </div>
      </header>';

require ("./index.html");}
else {

$db = new PDO("sqlite:youxiang.db");

$results = $db->query('SELECT * FROM gp where m_index='.'"'.$m_input1.'"')->fetchAll();
foreach ($results as $key=>$row) 
{
	//var_dump($row);
	$this_gp=$row['gp'];  //查找费率
	$this_baoe = $unit * $m_baof / $this_gp; //保费换算成保额
	//echo '<br />gp:'.$this_gp;
}

//这里显示调示数据用
//echo $this_ad_r;
//echo $this_ad_c;
//echo $yanshi;

?>
<h2>幸福人生（优享版）案例演示</h2>
<table width="90%" border="1">
  <tr>
    <th scope="col">投保年龄</th>
    <th scope="col">性别</th>
    <th scope="col">交费期间</th>
    <th scope="col">年交保费</th>
    <th scope="col">保险金额</th>
    <th scope="col">保障期间</th>
    <th scope="col">单位</th>
  </tr>
  <tr>
    <td><?php echo $m_age; ?>岁</td>
    <td><?php echo $m_sex_c; ?></td>
    <td><?php echo $m_ppp; ?>年</td>
    <td><?php echo $m_baof; ?></td>
    <td><?php echo number_format($this_baoe); ?></td>
    <td>至<?php echo $baoxian_time; ?>周岁</td>
    <td>元</td>
  </tr>

</table>

<h3>幸福人生（优享版）利益演示表 </h3>
<table  width="90%" border="1" summary="利益演示表，非官方">
  <caption>
    
  </caption>
  <tr>
    <th rowspan="2" scope="col">保单<br />
    年度</th>
    <th rowspan="2" scope="col">年龄</th>
    <th rowspan="2" scope="col">当年度保费</th>
    <th rowspan="2" scope="col">累计保费</th>
    <th rowspan="2" scope="col">现金价值</th>
    <th rowspan="2" scope="col">身故保险金</th>
    <th rowspan="2" scope="col">年金/满期金</th>

    <th colspan="2" scope="col">红利利益（<?php echo $this_ad_c; ?>红利）</th>
  </tr>
  <tr>
    <th scope="col">当年度红利</th>
    <th scope="col">累积红利</th>
  </tr>

<?php
$i=1;

for ($m_age; $m_age<105; $m_age++) {
	if (($i<10 or $yanshi==0) or ($i>=10 and $m_age%5==4 and $yanshi==1)) {
	if ($i % 2 == 0) {echo '
	<tr  class="alt">  
    <td>';}		//隔行显示
	else {echo '
	<tr> 
    <td>';}

	echo $i; //保单年度
	echo '</td> 
	<td>';  //第一列结束
	echo $m_age + 1;  //年龄
	echo '</td> 
	<td>';  //第二列结束

	if ($i<=$m_ppp) {echo $m_baof;  //年度保费
	}
	echo '</td> 
	<td>';  //年度保费列结束

	if ($i<=$m_ppp) {
		$sum_baof = $m_baof * $i;
		}
	else {
		$sum_baof = $m_baof * $m_ppp; 
	}
	echo $sum_baof;
	echo '</td> 
	<td>';  //累计保费列结束

$m_input2=$m_input1.'A'.$i;
$results = $db->query('SELECT * FROM cv where m_index='.'"'.$m_input2.'"')->fetchAll();
foreach ($results as $key=>$row) 
{
	//var_dump($row);
	$this_cv=$row['cv'];  //查找现金价值
	$this_rv0=$row['rv'];  //查找vnp
	$this_xianjia = $this_cv * $this_baoe / $unit;

	//echo $m_input2;
	//echo '-';
	echo number_format($this_xianjia,0);

}

$j=$i-1;
$m_input3=$m_input1.'A'.$j;
$results = $db->query('SELECT * FROM cv where m_index='.'"'.$m_input3.'"')->fetchAll();
foreach ($results as $key=>$row) 
{
	//var_dump($row);
	$this_vnp=$row['vnp'];  //查找vnp
	$this_rv=$row['rv'];  //查找rv
	$this_cd=$row['cd'];  //查找sb
	if ($j==0) {
		$this_fenhong_basic = $this_rv0  * $this_baoe / $unit;
	}
	else {$this_fenhong_basic = ($this_vnp + $this_rv - $this_cd) * $this_baoe / $unit;
	}
}
	//现金价值列
	echo '</td> 
	<td>';  //现金价值列结束


	echo number_format(max($sum_baof, $this_xianjia));  //身故保险金列
	echo '</td> 
	<td>';  //身故保险金结束


	if ($i<5) {
		$nianjin = '';
		}
	else {
		
		if ($m_age<59) {
		$nianjin = number_format($this_baoe * 0.1,2); 
		}
		else {
			if ($m_age<104) {
				$nianjin = number_format($this_baoe * 0.2 ,2); 
			}
			else 
			$nianjin = number_format($this_baoe * 0.2+ $sum_baof,2); 
		}
	}

	echo $nianjin;  //年金列
	echo '</td> 
	<td>';  //年金列结束

	$this_fenhong = $this_fenhong_basic * $this_ad_r;
	$total_fenhong =  $this_fenhong + $total_fenhong * ($total_r+1);

	echo number_format($this_fenhong,2);  //当年分红列
	echo '</td> 
	<td>';  //当年分红列结束
	
	echo number_format($total_fenhong, 2);

	echo '</td> 
	</tr>';  // 列结束

	
	 $i=$i+1;
	}
}

}
?> 
</table>

<p>&nbsp;</p>

</body>
</html>