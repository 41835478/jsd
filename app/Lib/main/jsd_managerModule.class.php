<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------
require 'core/JsdModule.class.php';

class jsd_managerModule extends JsdModule 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    //客户投诉详情
    public function user_complaints_detail()
    {
        $GLOBALS['tmpl']->display("jsd/manager_user_complaints_detail.html");
    }
    
    //用户详情页面
    public function user_detail_info()
    {
        $GLOBALS['tmpl']->display("jsd/manager_user_detail_info.html");
    }

    //名下技师列表
    public function self_technician_list() 
    {
        $GLOBALS['tmpl']->display("jsd/manager_technician_list.html");
    }
    
    //名下技师订单列表
    public function self_technician_order_list() 
    {
        $GLOBALS['tmpl']->display("jsd/manager_technician_order_list.html");
    }
    
    //名下技师订单跟踪
    public function self_technician_order_track() 
    {
        $GLOBALS['tmpl']->display("jsd/manager_technician_order_track.html");
    }
    
    //名下技师订单退款
    public function self_technician_order_refund() 
    {
        $GLOBALS['tmpl']->display("jsd/manager_technician_order_track.html");
    }
    
    //名下技师详情
    public function self_technician_detail_info() 
    {
        $GLOBALS['tmpl']->display("jsd/manager_technician_detail_info.html");
    }

}

?>