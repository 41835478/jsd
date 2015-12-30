<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="centent_obg" >
    <section class="gradient">
        <ul id="mmmm" class="percent">
            <li  class="percent_1">
                <p class="position"></p>
                <p class="position_1"></p>
                <a href="#">登录</a>
            </li>
        </ul>
        <ul class="bj_position">
        </ul>
        <ul class="rgba_bototn"></ul>
        <ul class="registered">
            <a class="fl diyi" href="<?php echo $this->_var['index_url']; ?>"></a>
            <a class="fr" href="<?php echo $this->_var['register_url']; ?>">注册</a>
        </ul>
    </section>
    <section class="The_form">
        <ul>
            <li>
                <span>手机号</span>
                <input type="text" placeholder="请输入手机号" id="mobile" onblur="mobile_check()">
            </li>
            <li>
                <span>密码</span>
                <input type="password" placeholder="请输入密码" id="user_pwd" onblur="pwd_check()">
            </li>
            <li class="botton">
                <input type="submit" value="登录" onclick="login_form()">
            </li>
        </ul>
        <div class="youqi"><a href="#">忘记密码？</a> </div>
    </section>
</section>
<?php echo $this->fetch('jsd/common_notice_box.html'); ?>
<script>
    function login_form(){
        if(mobile_check() && pwd_check()){
            var mobile = $('#mobile').val();
            var user_pwd = $('#user_pwd').val();
            
            $.ajax({
                type : 'post',
                url : '<?php echo $this->_var['ajax_login_url']; ?>',
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
                        $('#message_box').siblings(".guan_2").click(function(){
                            window.location.href="<?php echo $this->_var['mypage_url']; ?>";
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
        if(pwd == ''){
            $(".queren").show();
            $(".complete").show();
            message_box_show('请输入密码');
            return false;
        }
        
        return true;
    }
</script>
<?php echo $this->fetch('jsd/user_footer.html'); ?>