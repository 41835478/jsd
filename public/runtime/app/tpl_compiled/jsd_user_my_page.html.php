<?php echo $this->fetch('jsd/user_login_header.html'); ?>
        <section class="My_order">
            <p>我的订单</p>
            <ul>
                <li class="top_nav_1">
                    <a href="<?php echo $this->_var['order_list_url']; ?>">
                        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/chakan_q.png">
                        <P>订单查看</P>
                    </a>
                </li>
                <li class="top_nav_2">
                    <a href="<?php echo $this->_var['order_compaints_url']; ?>">
                        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/tous.png">
                        <P>订单投诉</P>
                    </a>
                </li>
                <li class="top_nav_3">
                    <a href="<?php echo $this->_var['commont_list_url']; ?>">
                        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/pinj.png">
                        <p>评价</p>
                    </a>
                </li>
                <li class="top_nav_4">
                    <a href="<?php echo $this->_var['refund_url']; ?>">
                        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/tuik.png">
                        <P>退款</P>
                    </a>
                </li>
            </ul>
        </section>
    <section class="My_wallet">
        <p>我的钱包</p>
        <ul>
            <li class="erji_nav_1">
                <a href="<?php echo $this->_var['payment_url']; ?>">
                    <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/zhangh.png">
                    <P>账户充值</P>
                </a>
            </li>
            <li class="erji_nav_2">
                <a href="<?php echo $this->_var['balance_url']; ?>">
                    <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/yue.png">
                    <P>余额查询</P>
                </a>
            </li>
            <li class="erji_nav_3">
                <a href="<?php echo $this->_var['coupon_url']; ?>">
                    <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/youyh.png">
                    <p>优惠券</p>
                </a>
            </li>
        </ul>
    </section>
    <section class="Set_up_the">
        <a href="<?php echo $this->_var['setting_url']; ?>">设置</a>
    </section>
</section>
<?php echo $this->fetch('jsd/user_footer.html'); ?>