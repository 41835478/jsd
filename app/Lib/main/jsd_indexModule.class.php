<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

require 'core/JsdModule.class.php';

class jsd_indexModule extends JsdModule
{
    private $user;
    
    public function __construct() 
    {
        parent::__construct();
        $this->user = $this->is_authed();
        
    }

    public function index() 
    {
        $left_url = 'jsd_user#login';
        $left_name = '登录';
        
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            $user_type = $this->user['user_type'];
            switch ($user_type) {
                case TECHNICIAN://技师
                    $left_url = 'jsd_technician#';
                    break;
                case MANAGER://经理
                    $left_url = 'jsd_manager#';
                    break;
                default://普通用户
                    $left_url = 'jsd_user#my_page';
                    break;
            }
            
            $left_name = $this->user['user_name'];
        }
        
        //预约服务
        $GLOBALS['tmpl']->assign("service_list_url",url("index", 'jsd_user#service_list'));
        //预约技师
        $GLOBALS['tmpl']->assign("technician_list_url",url("index", 'jsd_user#technician_list'));
        //我的按摩
        $GLOBALS['tmpl']->assign("order_list_url",url("index", 'jsd_user#order_list'));

        $GLOBALS['tmpl']->assign("left_name",$left_name);
        $GLOBALS['tmpl']->assign("left_url",url("index", $left_url));
        $GLOBALS['tmpl']->display("jsd/index.html");
    }

}

?>