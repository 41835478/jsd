<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------
require 'core/JsdModule.class.php';

class jsd_userModule extends JsdModule 
{
    private $user;
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->user = $this->is_authed();
        if(!empty($this->user) && ($this->user['user_type'] != NORMAL_USER)){
            app_redirect(url("index", "jsd_index#index"));
        }
        
        $GLOBALS['jsd_user_data'] = $this->user;
    }
    
    //用户账户余额
    public function balance()
    {
        $GLOBALS['tmpl']->display("jsd/user_balance.html");
    }
    
    //评论列表
    public function comment_list() 
    {
        $GLOBALS['tmpl']->display("jsd/user_comment_list.html");
    }
    
    //用户评价订单
    public function comment() 
    {
        $GLOBALS['tmpl']->display("jsd/user_comment.html");
    }

    //用户登录
    public function login() 
    {
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            app_redirect(url("index", "jsd_user#my_page"));
        }
        
        $GLOBALS['tmpl']->assign("register_url",url("index", "jsd_user#register"));
        $GLOBALS['tmpl']->assign("ajax_login_url",url("index", "jsd_user#ajax_login"));
        $GLOBALS['tmpl']->assign("mypage_url",url("index", "jsd_user#my_page"));
        $GLOBALS['tmpl']->assign("page_title","用户登录");
        $GLOBALS['tmpl']->display("jsd/user_login.html");
    }
    
    public function ajax_login()
    {
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            app_redirect(url("index", "jsd_user#my_page"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        //验证手机号码格式
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:NULL;
        $is_mobile = preg_match('/1[34578]{1}\d{9}$/', $mobile);
        if((empty($is_mobile))){
            $data['status'] = FALSE;
            $data['info'] = "手机号不正确";
            ajax_return($data);
        }
        
        //查询手机是否被注册
        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$mobile."' and is_delete = 0");
        if(empty($user)){
            $data['status'] = FALSE;
            $data['info'] = "手机号未注册";
            ajax_return($data);
        }
        
        //检查密码是否正确
        $user_pwd = isset($_POST['user_pwd'])?$_POST['user_pwd']:NULL;
        if(empty($user_pwd)){
            $data['status'] = FALSE;
            $data['info'] = "请输入密码";
            ajax_return($data);
        }
        if(md5($user_pwd) != $user['user_pwd']){
            $data['status'] = FALSE;
            $data['info'] = "密码错误,请重新输入";
            ajax_return($data);
        }
        
        es_session::set('jsd_user_info', $user);
        $data['status'] = TRUE;
        $data['info'] = "登录成功";
        ajax_return($data);
    }

        //用户注册
    public function register() 
    {
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            app_redirect(url("index", "jsd_user#my_page"));
        }
        
        $GLOBALS['tmpl']->assign("ajax_send_sms_code_url",url("index", "jsd_user#ajax_send_sms_code"));
        $GLOBALS['tmpl']->assign("ajax_register_url",url("index", "jsd_user#ajax_register"));
        $GLOBALS['tmpl']->assign("login_url",url("index", "jsd_user#login"));
        $GLOBALS['tmpl']->assign("page_title","用户注册");
        $GLOBALS['tmpl']->display("jsd/user_register.html");
    }
    
    //ajax发送短信验证码
    public function ajax_send_sms_code()
    {
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            app_redirect(url("index", "jsd_user#my_page"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        //验证手机号码格式
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:NULL;
        $is_mobile = preg_match('/1[34578]{1}\d{9}$/', $mobile);
        if((empty($is_mobile))){
            $data['status'] = FALSE;
            $data['info'] = "手机号不正确";
            ajax_return($data);
        }
        
        //查询手机是否被注册
        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$mobile."' and is_delete = 0");
        if(!empty($user)){
            $data['status'] = FALSE;
            $data['info'] = "手机号已经被注册";
            ajax_return($data);
        }
        
        
        // 生成6位短信验证码并发送到手机
        $sms_code = rand(100000, 999999);
        vendor('AliSms.sms');
        $resp = sendSMSAli($mobile, $sms_code);

        if(!empty($resp)){
            es_session::set('sms_code',$sms_code);
            $data['status'] = TRUE;
            $data['info'] = "发送成功";
            ajax_return($data);
        }
        
        $data['status'] = FALSE;
        $data['info'] = "发送短信失败,稍后请重试";
        ajax_return($data);
    }

    //ajax注册
    public function ajax_register()
    {
        //已经登录则跳转到mypage
        if(!empty($this->user)){
            app_redirect(url("index", "jsd_user#my_page"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        //验证手机号码格式
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:NULL;
        $is_mobile = preg_match('/1[34578]{1}\d{9}$/', $mobile);
        if((empty($is_mobile))){
            $data['status'] = FALSE;
            $data['info'] = "请输入11位手机号";
            ajax_return($data);
        }
        
        //查询手机是否被注册
        $have_register = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$mobile."' and is_delete = 0");
        if(!empty($have_register)){
            $data['status'] = FALSE;
            $data['info'] = "手机号已经被注册";
            ajax_return($data);
        }
        
        //验证密码
        $user_pwd = isset($_POST['user_pwd'])?$_POST['user_pwd']:NULL;
        $user_pwd_rep = isset($_POST['user_pwd_rep'])?$_POST['user_pwd_rep']:NULL;
        if(empty($user_pwd) || empty($user_pwd_rep)){
            $data['status'] = FALSE;
            $data['info'] = "请输入密码";
            ajax_return($data);
        }
        if($user_pwd != $user_pwd_rep){
            $data['status'] = FALSE;
            $data['info'] = "您两次输入的密码不匹配";
            ajax_return($data);
        }
        
        //验证短信验证码
        $sms_code = isset($_POST['sms_code'])?$_POST['sms_code']:NULL;
        if(empty($sms_code))
        {
            $data['status'] = FALSE;
            $data['info'] = "请输入收到的验证码";
            ajax_return($data);
        }
        
        if($sms_code != es_session::get('sms_code'))
        {
            $data['status'] = FALSE;
            $data['info'] = "验证码输入错误";
            ajax_return($data);
        }
        
        $user_data = array();
        $user_data['mobile'] = $mobile;
        $user_data['user_pwd'] = md5($user_pwd);
        
        require_once APP_ROOT_PATH."system/model/user.php";
        $result = auto_create($user_data, REGISTER_MOBILE_MOD);
        if($result['status']){
            $data['status'] = true;
            $data['info'] = "注册成功";
            ajax_return($data);
        }
        
        $data['status'] = FALSE;
        $data['info'] = "注册失败,稍后请重试";
        ajax_return($data);
    }

        //用户注册
    public function order_complaint() 
    {
        $GLOBALS['tmpl']->display("jsd/user_order_complaint.html");
    }
    
    //用户注册
    public function order_list() 
    {
        $GLOBALS['tmpl']->display("jsd/user_order_list.html");
    } 
    
    //用户注册
    public function my_page() 
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //我的订单
        $GLOBALS['tmpl']->assign("order_list_url",url("index", "jsd_user#order_list"));
        $GLOBALS['tmpl']->assign("order_compaints_url",url("index", "jsd_user#order_complaint"));
        $GLOBALS['tmpl']->assign("commont_list_url",url("index", "jsd_user#comment_list"));
        $GLOBALS['tmpl']->assign("refund_url",url("index", "jsd_user#refund"));
        
        //我的钱包
        $GLOBALS['tmpl']->assign("payment_url",url("index", "jsd_user#payment"));
        $GLOBALS['tmpl']->assign("balance_url",url("index", "jsd_user#balance"));
        $GLOBALS['tmpl']->assign("coupon_url",url("index", "jsd_user#coupon"));
        
        //设置
        $GLOBALS['tmpl']->assign("setting_url",url("index", "jsd_user#setting"));
        
        $GLOBALS['tmpl']->assign("page_title","个人中心");
        $GLOBALS['tmpl']->display("jsd/user_my_page.html");
    }
    
    public function booking()
    {
        $GLOBALS['tmpl']->display("jsd/user_booking.html");
    }
    
    public function payment()
    {
        $GLOBALS['tmpl']->display("jsd/user_payment.html");
    }
    
    public function refund()
    {
        $GLOBALS['tmpl']->display("jsd/user_refund.html");
    }
    
    public function service_list()
    {
        $GLOBALS['tmpl']->display("jsd/user_service_list.html");
    }
    
    public function service_detail()
    {
        $GLOBALS['tmpl']->display("jsd/user_service_detail.html");
    }
    
    public function setting()
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //地区列表
        $region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2");  //二级地址
        foreach($region_lv2 as $k=>$v)
        {
            if($v['id'] == intval($this->user['province_id']))
            {
                $region_lv2[$k]['selected'] = 1;
                break;
            }
        }
        $region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".intval($this->user['province_id']));  //三级地址
        foreach($region_lv3 as $k=>$v)
        {
            if($v['id'] == intval($this->user['city_id']))
            {
                $region_lv3[$k]['selected'] = 1;
                break;
            }
        }
        
        $GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
        $GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
        $GLOBALS['tmpl']->assign("page_title","设置");
        $GLOBALS['tmpl']->assign("jsd_user_data", $this->user);
        $GLOBALS['tmpl']->display("jsd/user_setting.html");
    }
    
    public function refund_detail()
    {
        $GLOBALS['tmpl']->display("jsd/user_refund_detail.html");
    }

    //技师列表
    public function technician_list()
    {
        $GLOBALS['tmpl']->display("jsd/user_technician_list.html");
    }
    
    //技师列表
    public function technician_detail()
    {
        $GLOBALS['tmpl']->display("jsd/user_technician_detail.html");
    }
    
    //技师列表
    public function coupon()
    {
        $GLOBALS['tmpl']->display("jsd/user_coupon.html");
    }

}

?>