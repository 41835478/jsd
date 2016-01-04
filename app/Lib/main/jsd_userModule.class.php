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
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        $sql = "SELECT 
                    service.`service_id`,
                    service.`name`,
                    service.`price`,
                    service.`image`,
                    my_order.`order_id`,
                    my_order.`created_at`,
                    my_order.`is_comment`,
                    my_order.`status`,
                    my_order.`amount` 
                FROM ".DB_PREFIX."jsd_order AS my_order 
                LEFT JOIN 
                    ".DB_PREFIX."jsd_technician_service AS service 
                ON 
                    my_order.`service_id` = service.`service_id` 
                LEFT JOIN 
                    ".DB_PREFIX."user AS user
                ON 
                    my_order.`user_id` = user.`id` 
                WHERE user.`id` = ".$this->user['id']." 
                AND my_order.`is_user_delete` = 0
                AND my_order.`status` = 3 
                ORDER BY my_order.`created_at`";
        
        $data = $GLOBALS['db']->getAll($sql);
        
        $new_data = array();
        $done_data = array();
        
        foreach ($data as $value) {
            if($value['is_comment'] == 0 ){
                $value['sum'] = $value['price'] * $value['amount'];
                $value['order_url'] = url('index', 'jsd_user#comment', array('order_id'=>$value['order_id']));
                $new_data[] = $value;
            }
            
            if($value['is_comment'] == 1 ){
                $value['sum'] = $value['price'] * $value['amount'];
                $value['order_url'] = url('index', 'jsd_user#comment', array('order_id'=>$value['order_id']));
                $done_data[] = $value;
            }
        }
        
        $GLOBALS['tmpl']->assign("sum",$new_data);
        $GLOBALS['tmpl']->assign("new_data",$new_data);
        $GLOBALS['tmpl']->assign("done_data",$done_data);
        $GLOBALS['tmpl']->assign("comment_url",url("index", "jsd_user#comment"));
        $GLOBALS['tmpl']->assign("ajax_delete_order_url",url("index", "jsd_user#ajax_delete_order"));
        
        
        $GLOBALS['tmpl']->assign("page_title","评价列表");
        $GLOBALS['tmpl']->display("jsd/user_comment_list.html");
    }
    
    //用户评价订单
    public function comment() 
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        $order_id = isset($_GET['order_id'])?intval($_GET['order_id']):NULL;
        if(empty($order_id)){
            app_redirect(url("index", "jsd_index"));
        }
        
        $sql = "SELECT
                tech.`technician_id` AS tech_id,
                tech.`name` AS tech_name,
                tech.`level` AS tech_level,
                service.`name` AS service_name,
                service.`price`,
                service.`image`,
                jsd_order.`order_id`,
                jsd_order.`amount`,
                jsd_order.`done_time`
              FROM
                ".DB_PREFIX."jsd_order AS jsd_order 
                LEFT JOIN ".DB_PREFIX."jsd_technician_service AS service 
                  ON jsd_order.`service_id` = service.`service_id` 
                LEFT JOIN ".DB_PREFIX."jsd_technician AS tech 
                  ON service.`technician_id` = tech.`technician_id` 
              WHERE jsd_order.`user_id` = ".$this->user['id']." 
                AND jsd_order.`order_id` = ".$order_id." 
                AND jsd_order.`is_comment` = 0 ";
        
        $data = $GLOBALS['db']->getRow($sql);
        if(empty($data)){
            exit('订单不存在或者订单不属于本人');
        }
        
        for ($i=0;$i<$data['tech_level'];$i++){
            $new_level[] = $i;
        }
        $data['tech_level'] = $new_level;
        
        $GLOBALS['tmpl']->assign("page_title","评论订单");
        $GLOBALS['tmpl']->assign("ajax_comment_url",url("index", "jsd_user#ajax_comment"));
        $GLOBALS['tmpl']->assign("comment_list_url",url("index", "jsd_user#comment_list"));
        $GLOBALS['tmpl']->assign("order_data",$data);
        $GLOBALS['tmpl']->display("jsd/user_comment.html");
    }
    
    public function ajax_comment()
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        $comment = !empty($_POST['comment'])?$_POST['comment']:NULL;
        
        $order_id = !empty($_POST['order_id'])?$_POST['order_id']:NULL;
        $tech_id = !empty($_POST['tech_id'])?$_POST['tech_id']:NULL;
        $anony = !empty($_POST['comment'])?$_POST['comment']:NULL;//1公开 2匿名
        $star_number = !empty($_POST['star_number'])?$_POST['star_number']:NULL;
        
        if(empty($order_id) || empty($tech_id) || empty($anony) || empty($star_number)){
            $data['status'] = FALSE;
            $data['info'] = "参数错误";
            ajax_return($data);
        }
        
        $is_anony = 1;
        if($anony == 1){
            $is_anony = 0;
        }

        //插入评论
        $sql_comment = "INSERT ".DB_PREFIX."jsd_comment (
                            comment,
                            order_id,
                            is_anony,
                            created_at,
                            updated_at
                          ) 
                          VALUES(
                            '".$comment."',
                            '".$order_id."',
                            '".$is_anony."',
                            '".  date('Y-m-d H:i:s')."',
                            '".  date('Y-m-d H:i:s')."'
                          )";
        $GLOBALS['db']->query($sql_comment);
        $comment_id = $GLOBALS['db']->insert_id()?$GLOBALS['db']->insert_id():NULL;
        
        if(empty($comment_id)){
            $data['status'] = FALSE;
            $data['info'] = "评论添加错误";
            ajax_return($data);
        }
        //打分
        $sql_star = "INSERT ".DB_PREFIX."jsd_star (
                            order_id,
                            user_id,
                            technician_id,
                            score,
                            created_at,
                            updated_at
                          ) 
                          VALUES(
                            '".$order_id."',
                            '".$this->user['id']."',
                            '".$tech_id."',
                            '".$star_number."',
                            '".  date('Y-m-d H:i:s')."',
                            '".  date('Y-m-d H:i:s')."'
                          )";
        $GLOBALS['db']->query($sql_star);
        $star_id = $GLOBALS['db']->insert_id()?$GLOBALS['db']->insert_id():NULL;
        
        if(empty($star_id)){
            $GLOBALS['db']->query("delete from ".DB_PREFIX."jsd_comment where comment_id=".$comment_id);
            $data['status'] = FALSE;
            $data['info'] = "打分错误";
            ajax_return($data);
        }
        //更新订单表
        $GLOBALS['db']->query(
                'UPDATE '.DB_PREFIX.'jsd_order 
                SET is_comment = 1,
                updated_at = "'.date('Y-m-d H:i:s').'"
                WHERE order_id = '.$order_id.' 
                AND user_id = '.$this->user['id']
                );
        $affected = $GLOBALS['db']->affected_rows()?$GLOBALS['db']->affected_rows():NULL;
        if(empty($affected)){
            $GLOBALS['db']->query("delete from ".DB_PREFIX."jsd_comment where comment_id=".$comment_id);
            $GLOBALS['db']->query("delete from ".DB_PREFIX."jsd_star where star_id=".$star_id);
            $data['status'] = FALSE;
            $data['info'] = "订单更新失败";
            ajax_return($data);
        }
        
        $data['status'] = TRUE;
        $data['info'] = "评论成功";
        ajax_return($data);
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
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        $sql = "SELECT 
                    service.`service_id`,
                    service.`name`,
                    service.`price`,
                    service.`image`,
                    my_order.`order_id`,
                    my_order.`created_at`,
                    my_order.`is_comment`,
                    my_order.`status`,
                    my_order.`amount` 
                FROM ".DB_PREFIX."jsd_order AS my_order 
                LEFT JOIN 
                    ".DB_PREFIX."jsd_technician_service AS service 
                ON 
                    my_order.`service_id` = service.`service_id` 
                LEFT JOIN 
                    ".DB_PREFIX."user AS user
                ON 
                    my_order.`user_id` = user.`id` 
                WHERE user.`id` = ".$this->user['id']." 
                AND my_order.`is_user_delete` = 0 
                ORDER BY my_order.`created_at`";
        
        $data = $GLOBALS['db']->getAll($sql);
        
        $new_data = array();
        $done_data = array();
        
        foreach ($data as $value) {
            if($value['status'] == ORDER_STATUS_DOING ){
                $value['sum'] = $value['price'] * $value['amount'];
//                $value['order_url'] = url('index', 'jsd_user#comment', array('order_id'=>$value['order_id']));
                $new_data[] = $value;
            }
            
            if($value['status'] == ORDER_STATUS_DONE ){
                $value['sum'] = $value['price'] * $value['amount'];
                $value['order_url'] = url('index', 'jsd_user#comment', array('order_id'=>$value['order_id']));
                $done_data[] = $value;
            }
        }
        
        $GLOBALS['tmpl']->assign("sum",$new_data);
        $GLOBALS['tmpl']->assign("new_data",$new_data);
        $GLOBALS['tmpl']->assign("done_data",$done_data);
        $GLOBALS['tmpl']->assign("comment_url",url("index", "jsd_user#comment"));
        $GLOBALS['tmpl']->assign("ajax_cancel_order_url",url("index", "jsd_user#ajax_cancel_order"));
        $GLOBALS['tmpl']->assign("ajax_delete_order_url",url("index", "jsd_user#ajax_delete_order"));
                
        $GLOBALS['tmpl']->assign("page_title","我的订单");
        $GLOBALS['tmpl']->display("jsd/user_order_list.html");
    } 
    
    public function ajax_cancel_order()
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        $order_id = !empty($_POST['order_id'])?$_POST['order_id']:NULL;
        if(empty($order_id)){
            $data['status'] = FALSE;
            $data['info'] = "订单不存在";
            ajax_return($data);
        }
        
        $sql = 'UPDATE '.DB_PREFIX.'jsd_order 
                SET
                    STATUS = 4 
                WHERE order_id = '.$order_id.' 
                AND user_id = '.$this->user['id'];
        
        $res = $GLOBALS['db']->query($sql);
        if(empty($res)){
            $data['status'] = TRUE;
            $data['info'] = "订单取消失败";
            ajax_return($data);
        }
        
        $data['status'] = TRUE;
        $data['info'] = "订单取消成功";
        ajax_return($data);
        
        
    }
    
    public function ajax_delete_order()
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        $order_id = !empty($_POST['order_id'])?$_POST['order_id']:NULL;
        if(empty($order_id)){
            $data['status'] = FALSE;
            $data['info'] = "订单不存在";
            ajax_return($data);
        }
        
        $sql = 'UPDATE '.DB_PREFIX.'jsd_order 
                SET
                    is_user_delete = 1 
                WHERE order_id = '.$order_id.' 
                AND user_id = '.$this->user['id'];
        
        $res = $GLOBALS['db']->query($sql);
        if(empty($res)){
            $data['status'] = TRUE;
            $data['info'] = "订单删除失败";
            ajax_return($data);
        }
        
        $data['status'] = TRUE;
        $data['info'] = "订单删除成功";
        ajax_return($data);
        
        
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
        $GLOBALS['tmpl']->assign("ajax_setting_url",url("index", "jsd_user#ajax_setting"));
        $GLOBALS['tmpl']->assign("jsd_user_data", $this->user);
        $GLOBALS['tmpl']->display("jsd/user_setting.html");
    }
    
    public function ajax_setting() 
    {
        //没有登录则跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        //更新类型
        $update_type = isset($_POST['update_type'])?$_POST['update_type']:NULL;
        if((empty($update_type))){
            $data['status'] = FALSE;
            $data['info'] = "当前无修改";
            ajax_return($data);
        }
        $province_id = isset($_POST['province_id'])?$_POST['province_id']:NULL;
        $city_id = isset($_POST['city_id'])?$_POST['city_id']:NULL;
        $byear = isset($_POST['byear'])?$_POST['byear']:NULL;
        $bmonth = isset($_POST['bmonth'])?$_POST['bmonth']:NULL;
        $bday = isset($_POST['bday'])?$_POST['bday']:NULL;
        $email = isset($_POST['email'])?$_POST['email']:NULL;
        $address_detail = isset($_POST['address_detail'])?$_POST['address_detail']:NULL;
        $user_name = isset($_POST['user_name'])?$_POST['user_name']:NULL;
        $origin_pwd = isset($_POST['origin_pwd'])?$_POST['origin_pwd']:NULL;
        $new_pwd = isset($_POST['new_pwd'])?$_POST['new_pwd']:NULL;
        $new_pwd_rep = isset($_POST['new_pwd_rep'])?$_POST['new_pwd_rep']:NULL;
                    
        switch ($update_type) {
            case SETTING_BASE_INFO:
                    //检查基本信息是否正确
                    $is_passed = $this->_update_base_info_check($email,$address_detail,$user_name);
                    
                    if($is_passed){
                        $update_data['province_id'] = $province_id;
                        $update_data['city_id'] = $city_id;
                        $update_data['byear'] = $byear;
                        $update_data['bmonth'] = $bmonth;
                        $update_data['bday'] = $bday;
                        
                        $update_data['email'] = $email;
                        $update_data['address_detail'] = $address_detail;
                        $update_data['user_name'] = $user_name;
                    }
                break;
            case SETTING_PWD:
                    //检查密码是否正确
                    $is_passed = $this->_update_pwd_check($origin_pwd,$new_pwd,$new_pwd_rep);
                    
                    if($is_passed){
                        $update_data['user_pwd'] = $new_pwd;
                    }
                break;
            case SETTING_BASE_INFO_PWD:
                    //检查基本信息是否正确
                    $is_base_info_passed = $this->_update_base_info_check($email,$address_detail,$user_name);
                    //检查密码是否正确
                    $is_pwd_passed = $this->_update_pwd_check($origin_pwd,$new_pwd,$new_pwd_rep);
                    
                    if(!empty($is_base_info_passed) && !empty($is_pwd_passed)){
                        $update_data['province_id'] = $province_id;
                        $update_data['city_id'] = $city_id;
                        $update_data['byear'] = $byear;
                        $update_data['bmonth'] = $bmonth;
                        $update_data['bday'] = $bday;
                        
                        $update_data['email'] = $email;
                        $update_data['address_detail'] = $address_detail;
                        $update_data['user_name'] = $user_name;
                        $update_data['user_pwd'] = $new_pwd;
                    }

                break;
            default:
                if(empty($email)){
                    $data['status'] = FALSE;
                    $data['info'] = "当前无修改或者修改出错,请稍后重试";
                    ajax_return($data);
                }
                break;
        }
        $update_data['id'] = $this->user['id'];
        require_once APP_ROOT_PATH."system/model/user.php";
        $res = save_user($update_data, 'UPDATE');
        if($res['status'] == 1){
            $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = '".intval($this->user['id'])."'");
            es_session::set("jsd_user_info",$user_info);
            
            $data['status'] = TRUE;
            $data['info'] = "更新成功";
            ajax_return($data);
        }
        
        $data['status'] = FALSE;
        $data['info'] = "更新数据错误！";
        ajax_return($data);
    }
    
    public function refund_detail()
    {
        $GLOBALS['tmpl']->display("jsd/user_refund_detail.html");
    }

    //技师列表
    public function technician_list()
    {
        //没有登录则跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        $sql = "SELECT 
                    t.`technician_id`,
                    t.`name`,
                    t.`address`,
                    t.`icon`,
                    t.`level`,
                    t.`introduction`
                  FROM
                    ".DB_PREFIX."jsd_technician_ability ab 
                    LEFT JOIN ".DB_PREFIX."jsd_technician_type tt 
                      ON ab.`technician_type_id` = tt.`technician_type_id` 
                    LEFT JOIN ".DB_PREFIX."jsd_technician t 
                      ON ab.`technician_id` = t.`technician_id` 
                  WHERE ab.`technician_type_id` = ".TECH_TYPE_ANMO." 
                  ORDER BY ab.`type_level` DESC,
                    t.`level` DESC";
        
        $data = $GLOBALS['db']->getAll($sql);
        foreach ($data as $key => $value) {
            for ($i=0;$i<$value['level'];$i++){
                $value['new_level'][] = $i;
            }
            $value['technician_detail_url'] = url('index', 'jsd_user#technician_detail', array('technician_id'=>$value['technician_id']));
            $data[$key] = $value;
        }
        
        $GLOBALS['tmpl']->assign("ajax_change_tech_type_url",url("index", "jsd_user#ajax_change_tech_type"));
        $GLOBALS['tmpl']->assign("tech_list",$data);
        $GLOBALS['tmpl']->assign("page_title","技师列表");
        $GLOBALS['tmpl']->display("jsd/user_technician_list.html");
    }
    
    public function ajax_change_tech_type()
    {
        //没有登录则跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        //检查发送类型
        if(empty($_POST)){
            $data['status'] = FALSE;
            $data['info'] = "请求失败";
            ajax_return($data);
        }
        
        $tech_type = isset($_POST['tech_type'])?$_POST['tech_type']:NULL;
        if(empty($tech_type)){
            $data['status'] = FALSE;
            $data['info'] = "参数错误";
            ajax_return($data);
        }
        
        $sql = "SELECT 
                    t.`technician_id`,
                    t.`name`,
                    t.`address`,
                    t.`icon`,
                    t.`level`,
                    t.`introduction`
                  FROM
                    ".DB_PREFIX."jsd_technician_ability ab 
                    LEFT JOIN ".DB_PREFIX."jsd_technician_type tt 
                      ON ab.`technician_type_id` = tt.`technician_type_id` 
                    LEFT JOIN ".DB_PREFIX."jsd_technician t 
                      ON ab.`technician_id` = t.`technician_id` 
                  WHERE ab.`technician_type_id` = ".$tech_type." 
                  ORDER BY ab.`type_level` DESC,
                    t.`level` DESC";
        
        $res_data = $GLOBALS['db']->getAll($sql);
        foreach ($res_data as $key => $value) {
            for ($i=0;$i<$value['level'];$i++){
                $value['new_level'][] = $i;
            }
            $value['technician_detail_url'] = url('index', 'jsd_user#technician_detail', array('technician_id'=>$value['technician_id']));
            $res_data[$key] = $value;
        }
        
        $data['status'] = TRUE;
        $data['info'] = "请求成功";
        $data['tech_list'] = $res_data;
        ajax_return($data);
        
    }

        //技师列表
    public function technician_detail()
    {
        //未登录跳转到login
        if(empty($this->user)){
            app_redirect(url("index", "jsd_user#login"));
        }
        
        $technician_id = isset($_GET['technician_id'])?intval($_GET['technician_id']):NULL;
        if(empty($technician_id)){
            app_redirect(url("index", "jsd_index"));
        }
        
        //基本信息
        $base_info_sql = "SELECT 
                            t.`name`,
                            IF(t.`gender` = 1, '男', '女') AS gender,
                            t.`level`,
                            t.`address`,
                            t.`service_area`,
                            t.`introduction`,
                            IF(tmp_order_count.`order_count` IS NULL,0,tmp_order_count.`order_count`) AS order_count
                          FROM
                            fanwe_jsd_technician AS t 
                            LEFT JOIN 
                              (SELECT 
                                tmp_t.`technician_id`,
                                COUNT(*) AS order_count 
                              FROM
                                fanwe_jsd_technician AS tmp_t 
                                LEFT JOIN fanwe_jsd_technician_service AS tmp_ts 
                                  ON tmp_t.`technician_id` = tmp_ts.`technician_id` 
                                LEFT JOIN fanwe_jsd_order AS o 
                                  ON tmp_ts.`service_id` = o.`service_id` 
                              WHERE o.`status` = 3) AS tmp_order_count 
                              ON t.`technician_id` = tmp_order_count.`technician_id` 
                          WHERE t.`technician_id` = ".$technician_id;
        $base_data = $GLOBALS['db']->getRow($base_info_sql);
        for ($i=0;$i<$base_data['level'];$i++){
            $base_data['new_level'][] = $i;
        }
        
        //服务列表
        $service_sql = "SELECT 
                            ts.`name`,
                            ts.`price`,
                            ts.`time`,
                            ts.`image`
                          FROM
                            fanwe_jsd_technician AS t 
                            RIGHT JOIN fanwe_jsd_technician_service AS ts 
                              ON t.`technician_id` = ts.`technician_id` 
                          WHERE t.`technician_id` = ".$technician_id;
        $service_data = $GLOBALS['db']->getAll($service_sql);
        
        $GLOBALS['tmpl']->assign("service_data",$service_data);
        $GLOBALS['tmpl']->assign("base_data",$base_data);
        $GLOBALS['tmpl']->assign("page_title","技师详情");
        $GLOBALS['tmpl']->display("jsd/user_technician_detail.html");
    }
    
    //技师列表
    public function coupon()
    {
        $GLOBALS['tmpl']->display("jsd/user_coupon.html");
    }

    private function _update_base_info_check($email,$address_detail,$user_name)
    {
        if(empty($email)){
            $data['status'] = FALSE;
            $data['info'] = "请输入邮箱账号";
            ajax_return($data);
        }

        if(!check_email($email)){
            $data['status'] = FALSE;
            $data['info'] = "邮箱格式不正确";
            ajax_return($data);
        }

        if(empty($address_detail)){
            $data['status'] = FALSE;
            $data['info'] = "请输入详细地址";
            ajax_return($data);
        }

        if(empty($user_name)){
            $data['status'] = FALSE;
            $data['info'] = "请输入称呼";
            ajax_return($data);
        }
        
        return TRUE;
    }
    
    private function _update_pwd_check($origin_pwd,$new_pwd,$new_pwd_rep)
    {
        if(empty($origin_pwd)){
            $data['status'] = FALSE;
            $data['info'] = "请输入旧密码";
            ajax_return($data);
        }

        //根据手机查询旧密码是否正确
        if(empty($new_pwd)){
            $data['status'] = FALSE;
            $data['info'] = "请输入旧密码";
            ajax_return($data);
        }

        if($new_pwd != $new_pwd_rep){
            $data['status'] = FALSE;
            $data['info'] = "请确认两次输入的密码一致";
            ajax_return($data);
        }
        
        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = '".$this->user['id']."' and is_delete = 0");
        if(md5($origin_pwd) != $user['user_pwd']){
            $data['status'] = FALSE;
            $data['info'] = "旧密码输入错误,请重新输入";
            ajax_return($data);
        }
        
        return TRUE;
    }
}

?>