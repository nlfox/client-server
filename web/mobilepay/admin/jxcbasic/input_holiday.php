<?
require ("../include/common.inc.php");
require ("../inputfile/inputfile.inc.php");

class tb_holiday extends inputfile 
{
	 var $file_headname =  array("假期名称","年份","公众假日");
	 var $file_headval  =  array("fd_holiday_name&0","fd_holiday_year&0","fd_holiday_date&0");
	 var $file_sqlname  =  "insert into tb_holiday ";
	 var $file_backurl  =  "../jxcbasic/tb_holiday_b.php";
	  var $file_inputhiden = array("fd_holiday_staid");  //固定插入数据字段
}
$tb_holiday_in = new tb_holiday ;
$tb_holiday_in->file_skin = $loginskin;
$tb_holiday_in->file_inputhidenvalue = array("0"=>array("fd_holiday_staid",$loginstaid));  //固定插入数据字段的值
$tb_holiday_in->main($isuseheaders,$selfileformat,$step,$excel_file,$excel_file_name,$thirdfilename,$fieldcheck,$fieldname) ;
?>
