<?php echo $this->fetch('jsd/user_login_header.html'); ?>
</section>
<section class="balance_box">
   <section class="top_yue">
       <ul>
           <li>当前余额<i>50000.00元</i><a href="#">充值</a></li>
       </ul>
   </section>
    <section class="Detailed">
        <p>交易明细<i>清空记录</i></p>
        <ul>
            <li>2015-12-01 10:24   充值1000元<em class="dianj">删除</em> </li>
            <li>2015-12-01 10:24   充值1000元<em class="dianj">删除</em> </li>
            <li>2015-12-01 18:15   扣除800元<em class="dianj">删除</em> </li>
            <li>2015-12-01 10:24   充值1000元<em class="dianj">删除</em> </li>
            <li>2015-12-01 18:15   扣除80元<em class="dianj">删除</em> </li>
            <li>2015-12-01 10:24   充值1000元<em class="dianj">删除</em> </li>
        </ul>
    </section>
</section>
<!--各种弹窗-->
<section class="queren" style="display:none">
    <ul>
        <li class="step" style="display:none">
            <p>确定要删除本条信息吗？</p>
            <i class="zhan">确定</i><i class="guan">取消</i>
        </li>
        <li class="complete" style="display:none">
            <span>删除成功</span>
            <p class="guan_2">确定</p>
        </li>
    </ul>
</section>
<?php echo $this->fetch('jsd/user_footer.html'); ?>