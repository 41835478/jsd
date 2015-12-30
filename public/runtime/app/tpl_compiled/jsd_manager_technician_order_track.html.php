<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="order_data_box">
    <section class="top_data truolky">
        <ul>
            <li class="return"><a href="index.html">返回</a> </li>
            <li class="phone">名下技师</li>
        </ul>
    </section>
</section>
<section class="order_trding_box" >
    <ul>
        <p>订单跟踪</p>
        <li>
            <p>
                <span>客户：</span>
                <i>张先生</i>
            </p>
            <p>
                <span>联系电话：</span>
                <i>18675345687</i>
            </p>
            <p>
                <span>订单地址：</span>
                <i> 高新区天府二街复城国际T4</i>
            </p>
        </li>
        <li>
            <p>
                <span>服务项目：</span>
                <em>头部按摩</em><em>足底按摩</em>
            </p>
            <p class="jindu">
                <span>服务进度：</span>
               <i>

                   <strong id="temp" max="100"  value="1"><img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/yuan.png"> <b id="cur"></b></strong>

               </i>
                <em>01:20:00</em>
                <!--<b id="temp" max="2400"  max="0" value="1"></b>-->
                <!--<b id="cur">0%</b>-->
                <!--<i>2400</i>-->
            </p>
        </li>
        <li class="banb2">
            <p>
                <span>客服评价：</span>
                <i>暂无</i>
            </p>
            <p>
                <span>投诉：</span>
                <i>无</i>
            </p>
        </li>
    </ul>
</section>
<section class="ziliao">
    <p>技师资料</p>
    <ul>
        <li class="shangmian">
            <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/show.png">
            <span class="zjong fl">
                <p>唐悠悠  女  </p>
                <p><img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png"><img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png"> <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/xing.png">  </p>
                <p>位置：天府二街</p>
            </span>
            <span class="fr right">
                <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/chakan.png">
                <p>222单</p>
            </span>
        </li>

        <li class="telte_2">
            <span>手机</span>
            <i>18543675865</i>
        </li>
        <li class="telte_2">
            <span>邮箱 </span>
            <i>youyou@163.com</i>
        </li>
        <li class="telte_2">
            <span>地址</span>
            <i>天府二街</i>
        </li>
        <li class="telte_2">
            <span>生日</span>
            <i>1999年1月25日</i>
        </li>
        <li class="telte_2">
            <span>称呼</span>
            <i>悠悠</i>
        </li>
        <li class="telte_2">
            <span>本月出单</span>
            <i>24</i>
        </li>
        <li class="telte_2">
            <span>上月出单</span>
            <i>25</i>
        </li>
    </ul>
</section>
<script>
    $(function(){
        var endTime = parseInt($("#temp").attr("max"));
        var startTime = 0;
       setInterval(function(){
           if(startTime<endTime){
               startTime+=1;
               $("#temp").attr("value",startTime);
               var rate = Math.floor((startTime/endTime)*100);
               $("#temp").css("width",rate+"%");
               $("#cur").text(rate+"%");
           }else{
               clearInterval();
           }
       },1000);
    });
</script>
<?php echo $this->fetch('jsd/manager_technician_footer.html'); ?>