<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="order_data_box">
    <section class="top_data">
        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/bj_3.png">
        <ul>
            <li class="return"><a href="index.html">返回</a> </li>
            <li class="phone"><i>18684357695</i></li>
        </ul>
    </section>
    <section class="jiayushijain">
            <span>成交时间：2015-12-10 20:20</span>
    </section>
    <section class="new_order comments">
        <p>技师：唐悠悠<img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png"><img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png"><img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png"> </p>
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
                </span>
            </li>

        </ul>
        <section class="texteuiu">
            <form>
                <ul>
                    <li>
                        <textarea></textarea>
                    </li>
                    <li class="nav">
                        <i>为TA打分：</i>
                        <a href="#javascript;" class="jia">1星</a>
                        <a href="#javascript;">2星</a>
                        <a href="#javascript;">3星</a>
                        <a href="#javascript;">4星</a>
                        <a href="#javascript;">5星</a>
                    </li>
                    <li class="yuanzge">
                        <em>是否匿名：</em>
                        <input type="radio" name="yuming">
                        <span>公开</span>
                        <input type="radio" name="yuming">
                        <span>匿名</span>
                    </li>
                    <li class="botton">
                        <input type="submit" value="提交评论">
                    </li>
                </ul>
            </form>
        </section>
    </section>

</section>
<?php echo $this->fetch('jsd/manager_technician_footer.html'); ?>