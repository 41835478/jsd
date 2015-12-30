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
<section class="jiesu_order">
    <ul>
        <li><b class="fl">头部按摩</b><p class="fr">X1</p></li>
        <li><span class="fl"><i>二星</i><i>45分钟</i></span><em class="fr">¥338</em></li>
    </ul>
</section>
<section class="jieushu_box">
    <ul class="diyi">
        <li class="add">
            <span>联系电话</span>
            <i>18684357695</i>
        </li>
        <li>
            <span>如何称呼</span>
            <i>王先生</i>
        </li>
        <li class="add">
            <span>服务地址</span>
            <i>高新区天府二街复城国际T4</i>
        </li>
        <li class="add">
            <span>上门时间</span>
            <i>2015-12-01 20:30:00</i>
        </li>
        <li>
            <span>特殊说明</span>
            <i>无</i>
        </li>

    </ul>
    <ul class="shijain">
        <li>倒计时时间：<i id="hour_show">01</i>：<i id="minute_show">10</i>：<i id="second_show">10</i></li>
    </ul>
</section>
<script type="text/javascript">
       var shi = $("#hour_show").text();
       var fen = $("#minute_show").text();
       var miao = $("#second_show").text();
       intDiff = parseInt(shi)*3600+parseInt(fen)*60+parseInt(miao);
           window.setInterval(function(){
                var day=0,
                        hour=0,
                        minute=0,
                        second=0;//时间默认值
                if(intDiff > 0){
                    day = Math.floor(intDiff / (60 * 60 * 24));
                    hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                    minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                    second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                $('#hour_show').html('<s id="h"></s>'+hour);
                $('#minute_show').html('<s></s>'+minute);
                $('#second_show').html('<s></s>'+second);
                intDiff--;
            }, 1000);
</script>
<?php echo $this->fetch('jsd/manager_technician_footer.html'); ?>