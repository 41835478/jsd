<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="order_data_box">
    <section class="top_data">
        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/bj_3.png">
        <ul>
            <li class="return"><a href="index.html">返回</a> </li>
            <li class="phone"><i>18684357695</i></li>
        </ul>
    </section>
</section>
<section class="refund_box refund_two">
    <ul>
        <li class="xinfzx">
            <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/show.png">
                <span class="fl data">
                    <p>头部按摩</p>
                    <p>38元</p>
                    <p>x1</p>
                </span>
                <span class="fr rig">
                    <p>申请退款</p>
                </span>
        </li>
        <li class="list_1">
            <span>退款原因</span>
            <i> 技师未按时到达</i>
        </li>
        <li class="list_1">
            <span>退款说明</span>
            <i> 无</i>
        </li>
        <li class="botton_5">
            <a class="fl" href="#">撤销申请</a>
        </li>
        <li class="tishi">
            <p>系统提示：您的退款申请已提交成功，请耐心等待处理结果，如有疑 问请致电：028-53465431</p>
        </li>
    </ul>
</section>
<?php echo $this->fetch('jsd/user_footer.html'); ?>