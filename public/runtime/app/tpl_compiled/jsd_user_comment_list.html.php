<?php echo $this->fetch('jsd/user_login_header.html'); ?>
    <section class="new_order">
        <p>待评价</p>
        <ul>
            <li>
                <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/show.png">
                <span class="fl data">
                    <p>头部按摩</p>
                    <p>38元</p>
                    <P>x1</P>
                </span>
                <span class="fr rig">
                    <p>交易成功</p>
                    <a href="#">评价</a>
                </span>
            </li>
            <i>2015-10-10 12:32  共1笔消费  合计：38元<a class="fr" href="#">删除订单</a> </i>
        </ul>
    </section>

    <section class="new_order">
        <p>已评价</p>
        <ul class="ca_wan">
            <li>
                <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/show.png">
                <span class="fl data">
                    <p>头部按摩</p>
                    <p>38元</p>
                    <P>x1</P>
                </span>
                <span class="fr rig">
                    <p>交易成功</p>
                    <a class="over" href="#">已评价</a>
                </span>
            </li>
            <i>2015-10-10 12:32  共1笔消费  合计：38元<a class="fr" href="#">删除订单</a> </i>
        </ul>

    </section>
</section>
<?php echo $this->fetch('jsd/user_footer.html'); ?>