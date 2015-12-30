<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------
require 'core/JsdModule.class.php';

class jsd_technicianModule extends JsdModule 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    //来自客户的评价
    public function comment_from_user()
    {
        $GLOBALS['tmpl']->display("jsd/technician_comment_from_user.html");
    }
    
    //来自客户的映像
    public function impression_from_users()
    {
        $GLOBALS['tmpl']->display("jsd/technician_impression_from_users.html");
    }
    
    //服务准备就绪
    public function service_stand_by()
    {
        $GLOBALS['tmpl']->display("jsd/technician_service_stand_by.html");
    }
    
    //服务进行中
    public function service_progressing()
    {
        $GLOBALS['tmpl']->display("jsd/technician_service_progressing.html");
    }

    //技师评论客户
    public function comment_to_user() 
    {
        $GLOBALS['tmpl']->display("jsd/technician_comment_to_user.html");
    }
    
    //技师接到的订单列表
    public function order_list() 
    {
        $GLOBALS['tmpl']->display("jsd/technician_order_list.html");
    }
    
    //技师接到的订单列表
    public function receive_order() 
    {
        $GLOBALS['tmpl']->display("jsd/technician_receive_order.html");
    }

}

?>