<?php echo $this->fetch('jsd/global_header.html'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/css/common.css">
<section class="content erccontent">
    <section class="header_box erccontent_1">
        <ul>
            <li class="title jiayti">
                <span><a class="no_q add" href="#">给自己预约</a><a class="add_1" href="#">给朋友预约</a></span>
            </li>
            <li class="lfo1">返回</li>
        </ul>
    </section>
    <section class="header_data">
        <ul>
            <li><b class="fl">头部按摩</b><p class="fr">X1</p></li>
            <li><span class="fl"><i>二星</i><i>45分钟</i></span><em class="fr">¥338</em></li>
        </ul>
    </section>
    <section class="frame" >
        <form>
            <ul>
                <li>
                    <span>联系电话</span>
                    <input type="text">
                </li>
                <li>
                    <span>如何称呼</span>
                    <input type="text">
                </li>
                <li>
                    <span>服务地址</span>
                    <input class="tancu" value="请选择服务地址" onfocus="if(this.value == '请选择服务地址'){this.value=''}"/>
                </li>
                <li class="shiajn">
                    <span>上门时间</span>
                      <input id="endTime" class="kbtn" value="请选择上门服务时间" onfocus="if(this.value == '请选择上门服务时间'){this.value=''}"/>
                </li>
                <li>
                    <span>特殊说明</span>
                    <input type="text">
                </li>
                <li class="botton">
                   <input type="submit" value="立即预约">
                </li>
            </ul>
        </form>
    </section>
    <section class="frame" style="display:none">
        <form>
            <ul>
                <li>
                    <span>联系电话</span>
                    <input type="text">
                </li>
                <li>
                    <span>如何称呼</span>
                    <input type="text">
                </li>
                <li>
                    <span>服务地址</span>
                    <input class="tancu" value="请选择服务地址" onfocus="if(this.value == '请选择服务地址'){this.value=''}"/>
                </li>
                <li class="shiajn">
                    <span>上门时间</span>
                    <input id="aendTime" class="kbtn" value="请选择上门服务时间" onfocus="if(this.value == '请选择上门服务时间'){this.value=''}"/>
                </li>
                <li>
                    <span>特殊说明</span>
                    <input type="text">
                </li>
                <li class="botton">
                    <input type="submit" value="立即预约">
                </li>
            </ul>
        </form>
    </section>
    <div id="datePlugin"></div>
    <div class="waicehng" style="display:none">
        <div class="tanchuobg">
        <select>
            <option>四川省</option>
        </select>
        <select>
            <option>成都市</option>
        </select>
        <select>
            <option>武侯区</option>
        </select>
        <select>
            <option>天府二街</option>
        </select>
        <ul>
            <li>
                <span style="float:left">详细地址:</span>
                <textarea></textarea>
                <p class="determine">确定</p>
                <p class="cancel">取消</p>
            </li>

        </ul>
    </div>
    </div>
</section>
<script type="text/javascript">
    $(function(){
//        $('#beginTime').date();
        $('#endTime').date({theme:"datetime"});
//        $('#aendTime').date();
        $('#aendTime').date({theme:"datetime"});
    });
</script>

<style type="text/css">
    .demo{width:300px;margin:40px auto 0 auto;}
    .demo .lie{margin:0 0 20px 0;}
</style>

</body>
</html>
