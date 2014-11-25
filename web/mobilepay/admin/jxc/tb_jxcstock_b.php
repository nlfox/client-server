<?
$thismenucode = "2k201";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_paycardstock_b extends browse {
	 //var $prgnoware    = array("刷卡器购销","刷卡器入库");
	 var $prgnoware    = array("刷卡器购销","入库办理");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_stock_id";
	 
	 var $browse_queryselect = "select fd_stock_id , fd_stock_no , fd_stock_suppno , fd_stock_suppname ,
	                            fd_stock_date ,  fd_stock_state,  fd_stock_allmoney,fd_stock_allquantity,
	                            fd_stock_ldr,fd_stock_dealwithman
	                            from tb_paycardstock	                       
	                           ";
	 var $browse_delsql = "delete from tb_paycardstock where fd_stock_id = '%s'" ;
	 var $browse_relatingdelsql = array ("1" => "delete from tb_paycardstockdetail  where fd_skdetail_stockid = '%s'" ); 
	 var $browse_new   = "jxcstock.php" ;
     var $browse_edit  = "jxcstock.php?listid=" ;
   
     var $browse_defaultorder = " CASE WHEN fd_stock_state = '0'
                               THEN 1                                  
                               WHEN fd_stock_state = '1'
                                THEN 2                           
                               END,fd_stock_date desc
                             ";	  

   
   var $browse_state = array("fd_stock_state");
   
   function makeedit($key) {	// 生成编辑连接
  	  $returnval = "" ;
   	  switch($this->arr_spilthfield[0]){  //判断记录是在那一个状态下，在那一个状态下就使用那一个连接
   	  	case "0":
   	  	  if(!empty($this->browse_edit)){
  	        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
  	      }
   	  	  break;
   	  	case "1":
   	  	  $returnval = "<a href=javascript:linkurl(\"stock_sq_view.php?backstate=0&listid=".$key."\")>查看</a>" ;;	
   	  	  break;
   	  }
  	  return $returnval;
    }
    
  // var $browse_relatingdelsql = array(
   //                             "0" => "delete from tb_paycardstockdetail  where fd_skdetail_stockid = '%s'"
   //                              );
   
   
   
	 var $browse_field = array("fd_stock_state","fd_stock_no","fd_stock_suppno","fd_stock_suppname","fd_stock_date","fd_stock_allquantity","fd_stock_allmoney","fd_stock_ldr","fd_stock_dealwithman");
 	 var $browse_find = array(		// 查询条件
				"0" => array("单据编号"      ,   "fd_stock_no"         ,"TXT"),
				"1" => array("供应商编号"    ,   "fd_stock_suppno"     ,"TXT"),
				"2" => array("供应商名称"    ,   "fd_stock_suppname"   ,"TXT"), 
				"3" => array("单据日期"      ,   "fd_stock_date"       ,"TXT"), 

				);

}

class fd_stock_no extends browsefield {
        var $bwfd_fdname = "fd_stock_no";	// 数据库中字段名称
        var $bwfd_title = "单据编号";	// 字段标题
}

class fd_stock_suppno extends browsefield {
        var $bwfd_fdname = "fd_stock_suppno";	// 数据库中字段名称
        var $bwfd_title = "供应商编号";	// 字段标题
}

class fd_stock_suppname extends browsefield {
        var $bwfd_fdname = "fd_stock_suppname";	// 数据库中字段名称
        var $bwfd_title = "供应商名称";	// 字段标题
}


class fd_stock_date extends browsefield {
        var $bwfd_fdname = "fd_stock_date";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}

class fd_stock_state extends browsefield {
        var $bwfd_fdname = "fd_stock_state";	// 数据库中字段名称
        var $bwfd_title = "状态";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch($this->bwfd_value){
        		case "0":
        		  $this->bwfd_show = "暂存";
        		  break;
        		case "1":
        		  $this->bwfd_show = "等待审批";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

class fd_stock_allquantity extends browsefield {
	var $bwfd_fdname = "fd_stock_allquantity"; // 数据库中字段名称
	var $bwfd_title = "总数量"; // 字段标题
	var $bwfd_align = "right";
}

class fd_stock_allmoney extends browsefield {
	var $bwfd_fdname = "fd_stock_allmoney"; // 数据库中字段名称
	var $bwfd_title = "总金额"; // 字段标题
	var $bwfd_align = "right";
}

class fd_stock_ldr  extends browsefield {
	var $bwfd_fdname = "fd_stock_ldr"; // 数据库中字段名称
	var $bwfd_title = "录单人"; // 字段标题
}

class fd_stock_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_stock_dealwithman"; // 数据库中字段名称
	var $bwfd_title = "经手人"; // 字段标题
}

// 链接定义
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_stock_id","")
   			    );  
   var $bwlk_title ="打印";	// link标题
   var $bwlk_prgname = "stockprint.php?listid=";	// 链接程序
   
   function makelink() {	// 生成链接
    $linkurl = $this->makeprg();
    $link  = "<a href=javascript:windowopen(\"".$linkurl."\")>".$this->bwlk_title."</a>";
    return $link;
  }
}
if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_paycardstock_bu = new tb_paycardstock_b ;
$tb_paycardstock_bu->browse_skin = $loginskin ;
$tb_paycardstock_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_paycardstock_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_paycardstock_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_paycardstock_bu->browse_link = array ("lk_view1" );

//显示有权限查看的机构资料
$tb_paycardstock_bu->browse_querywhere .= " (fd_stock_state = 0 or fd_stock_state = 1) ";

$tb_paycardstock_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>