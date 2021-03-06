<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="centent_obg" >
    <section class="gradient">
        <ul id="mmmm" class="percent">
            <li  class="percent_1">
                <p class="position"></p>
                <p class="position_1"></p>
                <a href="#">注册</a>
            </li>
        </ul>
        <ul class="bj_position">
        </ul>
        <ul class="rgba_bototn"></ul>
        <ul class="registered">
            <a class="fl diyi" href="<?php echo $this->_var['index_url']; ?>"></a>
            <a class="fr" href="<?php echo $this->_var['login_url']; ?>">登录</a>
        </ul>
    </section>
    <section class="register_biao">
        <ul>
            <li>
                <span>手机号</span>
                <input type="text" id="mobile" onblur="mobile_check()">
            </li>
            <li>
                <span>创建密码</span>
                <input type="password" id="user_pwd">
            </li>
            <li>
                <span>确认密码</span>
                <input type="password" id="user_pwd_rep" onblur="pwd_check()">
            </li>
            <li class="yuanx">
                <span>短信验证码</span>
                <input type="text" id="sms_code">
                <p class="fr" onclick="send_verify_code()" id="send_message_p">获取验证码</p>
            </li>
            <li class="botton">
                <input type="submit" value="注册" onclick="submit_form()">
                <p>注册即表示同意<a href="#">《点到服务协议》</a> </p>
            </li>
        </ul>
    </section>
</section>
<?php echo $this->fetch('jsd/common_notice_box.html'); ?>
<script>
    var wait=60;
    //发送验证码
    function send_verify_code(){
        var status = send_sms_code();
        if(status){
            var obj = $('#send_message_p').get(0);
            time(obj);
        }
    }
    
    //倒计时
    function time(e) {
        if (wait == 0) {
            e.setAttribute("href","javascript:void(0)");
            e.setAttribute("onclick","send_verify_code()");           
            e.innerHTML="发送验证码";
            e.style.background = '#ff6401';
            wait = 60;
        } else {
            e.removeAttribute("href");
            e.removeAttribute("onclick");
            e.style.background = '#666';
            e.innerHTML="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(e);
            },1000);
        }
    }
    
    function send_sms_code(){
        if(mobile_check() && pwd_check()){
            var mobile = $('#mobile').val();
            var user_pwd = $('#user_pwd').val();
            
            $.ajax({
                type : 'post',
                url : '<?php echo $this->_var['ajax_send_sms_code_url']; ?>',
                data : {'mobile' : mobile,'user_pwd' : user_pwd},
                dataType : 'json',
                success : function(data) 
                {
                    if(!data.status){
                        //错误消息
                        $(".queren").show();
                        $(".complete").show();
                        message_box_show(data.info);
                        return false;
                    }else{
                        //发送成功
                        $(".queren").show();
                        $(".complete").show();
                        message_box_show(data.info);
                    }
                }
            });
            
            return true;
        }
        
        return false;
    }
    
    function submit_form(){
        if(mobile_check() && pwd_check() && sms_code_check()){
            var mobile = $('#mobile').val();
            var user_pwd = $('#user_pwd').val();
            var user_pwd_rep = $('#user_pwd_rep').val();
            var sms_code = $('#sms_code').val();
            
            $.ajax({
                type : 'post',
                url : '<?php echo $this->_var['ajax_register_url']; ?>',
                data : {'mobile' : mobile,'user_pwd' : user_pwd,'user_pwd_rep':user_pwd_rep,'sms_code' : sms_code},
                dataType : 'json',
                success : function(data) 
                {
                    if(!data.status){
                        //错误消息
                        $(".queren").show();
                        $(".complete").show();
                        message_box_show(data.info);
                        return false;
                    }else{
                        //发送成功
                        $(".queren").show();
                        $(".complete").show();
                        
                        message_box_show(data.info);
                        $('#message_box').siblings(".guan_2").click(function(){
                            window.location.href="<?php echo $this->_var['login_url']; ?>";
                        });
                    }
                }
            });
        }
    }
    
    function mobile_check(){
        var mobile = $('#mobile').val();
        var rex = /1[34578]{1}\d{9}$/;
        if(!rex.test(mobile)){
            $(".queren").show();
            $(".complete").show();
            message_box_show('请输入11位手机号');
            return false;
        }
        
        return true;
    }
    
    function pwd_check(){
        var pwd = $('#user_pwd').val();
        var pwd_rep = $('#user_pwd_rep').val();
        if(pwd == '' || pwd_rep == ''){
            $(".queren").show();
            $(".complete").show();
            message_box_show('请输入密码');
            return false;
        }
        
        if((pwd != pwd_rep)){
            $(".queren").show();
            $(".complete").show();
            message_box_show('您两次输入的密码不匹配');
            return false;
        }
        
        return true;
    }
    
    function sms_code_check(){
        var sms_code = $('#sms_code').val();
        if(sms_code == ''){
            $(".queren").show();
            $(".complete").show();
            message_box_show('请输入收到的验证码');
            return false;
        }
        
        return true;
    }
    
</script>
<?php echo $this->fetch('jsd/user_footer.html'); ?>