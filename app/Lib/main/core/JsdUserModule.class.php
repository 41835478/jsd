<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

class JsdUserModule extends JsdModule
{
    public function __construct() 
    {
        parent::__construct();
        $GLOBALS['tmpl']->assign("index_url", url("index", "jsd_index#index"));
        $GLOBALS['tmpl']->assign("service_url", url("index", "jsd_user#service_list"));
        $GLOBALS['tmpl']->assign("technician_list_url", url("index", "jsd_user#technician_list"));
        $GLOBALS['tmpl']->assign("order_list_url", url("index", "jsd_user#order_list"));
        $GLOBALS['tmpl']->assign("mypage_url", url("index", "jsd_user#my_page"));

    }
    
}
?>