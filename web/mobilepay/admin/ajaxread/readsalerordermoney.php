<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


//$querywhere =" and fd_organorder_organid='$loginpartid' ";
//$querywhere1 =" and fd_order_partid='$loginpartid' ";
$querysalerid=$tmpsalerid;

$query = "select fd_organmem_username,fd_organmem_id ,fd_order_alldunshu as allquantity,fd_order_no from web_order
join tb_organmem  on fd_order_memeberid = fd_organmem_id where fd_order_allmoney>'0' $querywhere1  ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $varr_id[]             = $db->f(fd_organmem_id);            
       $varr_memname[]       = g2u($db->f(fd_organmem_username))+1;  
	   $varr_allquantity[]       = $db->f(allquantity)+0; 
	   $varr_orderno[]       = $db->f(fd_order_no)+0;       
	}}
	//echo var_dump($varr_truename);
$query = "select * from web_saler";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $salerid                           = $db->f(fd_saler_id);           
	   $arr_salerid[$salerid][]           = $db->f(fd_saler_id); 
	   if($db->f(fd_saler_sharesalerid)>1)
	   {           
       $arr_salerid[$salerid][]           = $db->f(fd_saler_sharesalerid); 
	   //echo $db->f(fd_saler_sharesalerid);
	   }
	   $varr_salerid[$salerid]        = g2u($db->f(fd_saler_id));  
       $varr_truename[$salerid]       = g2u($db->f(fd_saler_truename));        
       $varr_idcard[$salerid]         = g2u($db->f(fd_saler_idcard)); 
       $varr_no[$salerid]             = g2u($db->f(fd_salercard_no));
	   $varr_phone[$salerid]          = g2u($db->f(fd_saler_phone));
	   
	}
}
//echo var_dump($arr_salerid);
//echo var_dump($arr_id).$loginpartid;
$query = "select * from  web_salerrewards where fd_rewards_type  = '2'"; 
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $selfmoney      = $db->f(fd_rewards_selfmoney);      
	    $upmoney         = $db->f(fd_rewards_upmoney);     
		$allmoney       = $db->f(fd_rewards_allmoney);
	}}
if(!empty($varr_id))
{
for($i=0;$i<count($varr_id);$i++)
{

$query = "select * from web_salercard 
left join web_saler on fd_saler_id = fd_salercard_salerid  where fd_salercard_memberid ='$varr_id[$i]'
 and fd_salercard_no like '%$idcard%' ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_salercard_id);            //id号 
	   $vsalerid        = g2u($db->f(fd_saler_id));  
	   
       if(count($arr_salerid[$vsalerid])>0)
	   {
	   for($j=0;$j<($arr_salerid[$vsalerid]);$j++)       
	   {
	   $vsalerid=$varr_salerid[$arr_salerid[$vsalerid][$j]]; 
	  if(empty($tmpsalerid)) $querysalerid =$vsalerid; 
	   if($vsalerid==$querysalerid and $querysalerid>0)
	   {
       $vtruename=$varr_truename[$arr_salerid[$vsalerid][$j]];        
       $vidcard=$varr_idcard[$arr_salerid[$vsalerid][$j]]; 
       $vno=$varr_no[$arr_salerid[$vsalerid][$j]];
	   $vphone =$varr_phone[$arr_salerid[$vsalerid][$j]];
	   $money="";
	   if(count($arr_salerid[$vsalerid])>1)
	   {
	   if($j==0) $money=$selfmoney*$varr_allquantity[$i];
	   if($j==1) $money=$upmoney*$varr_allquantity[$i];
	   }else
	   {
		if($j==0) $money=$allmoney*$varr_allquantity[$i];
	   }
	   $memname =$varr_memname[$i];
	   $orderno =$varr_orderno[$i];
	   $allquantity =$varr_allquantity[$i];
	   
	   $arr_list[] = array(
		                $vtruename,
						$memname,
						$orderno,
						$allquantity,
						$money+0
						);
     }
	   }
	}else
	{
	 $arr_list[] = array(
		                $vtruename,
						$memname,
						$orderno,
						$allquantity,
						$money+0);	
	}
	}
   }
   }
}
if($arr_list=="")
{
 $vmember  = "";
	 $arr_list[] = array(
		                       "",
						"",
						"",
						"",
						"");
}
        $returnarray['aaData']=$arr_list;
	    echo  json_encode($returnarray);
//显示帐户列表
function selectsalercard($sharesalerid)
{
$db  = new DB_test;

//显示帐户选择列表
$query = "select * from web_saler where fd_saler_id='$sharesalerid'" ;
$db->query($query);
if($db->nf()){
   while($db->next_record()){		
		   $arr_salerid     = $db->f(fd_saler_id)  ; 
		   $arr_saler       = $db->f(fd_saler_truename);    
   }
}
return $arr_saler;
}
?>