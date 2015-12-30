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
            <a class="fl diyi" href="index.html"></a>
            <a class="fr" href="login.html">登录</a>
        </ul>
    </section>
    <section class="register_biao">
        <ul>
            <li>
                <span>手机号</span>
                <input type="text">
            </li>
            <li>
                <span>创建密码</span>
                <input type="password" id="input1">
            </li>
            <li>
                <span>确认密码</span>
                <input type="password" id="input2">
            </li>
            <li class="yuanx">
                <span>短信验证码</span>
                <input type="text">
                <p class="fr">获取验证码</p>
            </li>
            <li class="botton">
                <input type="submit" value="注册"  onclick="check()">
                <p>注册即表示同意<a href="#">《点到服务协议》</a> </p>
            </li>
        </ul>
    </section>
</section>
<?php echo $this->fetch('jsd/footer.html'); ?>