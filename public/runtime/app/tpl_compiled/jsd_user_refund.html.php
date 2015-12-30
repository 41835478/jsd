<?php echo $this->fetch('jsd/user_login_header.html'); ?>
</section>
<section class="refund_box">
    <ul>
        <li class="xinfzx">
            <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/show.png">
                <span class="fl data">
                    <p>头部按摩</p>
                    <p>38元</p>
                    <p>x1</p>
                </span>
                <span class="fr rig">
                    <p>交易成功</p>
                </span>
        </li>
        <form>
            <li class="input">
                <span>退款原因</span>
                <select>
                    <option> 请选择退款原因</option>
                    <option> 不想按了</option>
                    <option> 其他</option>
                </select>
            </li>
            <li class="input">
                <span>退款说明</span>
                <input type="text" class="">
            </li>
            <li class="botton">
                <input type="submit" value="提交申请">
            </li>
        </form>
    </ul>
</section>
<?php echo $this->fetch('jsd/user_footer.html'); ?>