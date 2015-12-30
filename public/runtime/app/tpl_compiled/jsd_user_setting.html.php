<?php echo $this->fetch('jsd/user_login_header.html'); ?>
<script type="text/javascript" src="<?php echo $this->_var['APP_ROOT']; ?>/system/region.js"></script>
<section class="set_up">
    <form>
        <p class="tietin">个人资料</p>
        <ul class="zilao">
            <li>
                <span>手机</span>
                <i><?php echo $this->_var['jsd_user_data']['mobile']; ?></i>
            </li>
            <li>
                <span>邮箱</span>
                <input type="email" value="<?php echo $this->_var['jsd_user_data']['email']; ?>">
            </li>
            <li>
                <span>地址</span>
                <select id="settings_province_id" name="province_id" class="ui-select" height="200">
                        <option value="0">所在省份</option>
                        <?php $_from = $this->_var['region_lv2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv2');if (count($_from)):
    foreach ($_from AS $this->_var['lv2']):
?>
                        <option <?php if ($this->_var['lv2']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv2']['id']; ?>"><?php echo $this->_var['lv2']['name']; ?></option>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </select>
                <select id="settings_city_id" name="city_id" class="ui-select" height="200">
                        <option value="0">所在城市</option>		
                        <?php $_from = $this->_var['region_lv3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv3');if (count($_from)):
    foreach ($_from AS $this->_var['lv3']):
?>
                        <option <?php if ($this->_var['lv3']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv3']['id']; ?>"><?php echo $this->_var['lv3']['name']; ?></option>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </select>
                <input type="text" value="">
            </li>
            <li>
                <span>生日</span>
                <input type="text" value="<?php echo $this->_var['jsd_user_data']['byear']; ?>-<?php echo $this->_var['jsd_user_data']['bmonth']; ?>-<?php echo $this->_var['jsd_user_data']['bday']; ?>">
            </li>
            <li>
                <span>称呼</span>
                <input type="text" value="<?php echo $this->_var['jsd_user_data']['user_name']; ?>">
            </li>
        </ul>
        <ul class="paoswodr">
            <p class="tietin">修改密码</p>
            <li>
                <span>旧密码</span>
                <input type="password">
            </li>
            <li>
                <span>设置新密码</span>
                <input type="password">
            </li>
            <li>
                <span>确认新密码</span>
                <input type="password">
            </li>
        </ul>
        <ul class="botton">
            <input type="submit" value="确认修改">
        </ul>
    </form>
</section>
</section>

<script>
    //切换地区
    $(document).ready(function(){	
        $("select[name='province_id']").bind("change",function(){
            load_city();
        });
    })

    //载入城市
    function load_city()
    {
        var id = $("select[name='province_id']").val();
        var evalStr="regionConf.r"+id+".c";
        if(id==0){
            var html = "<option value='0'>所在城市</option>";
        }else{
            var regionConfs=eval(evalStr);
            evalStr+=".";
            var html = "<option value='0'>所在城市</option>";
            for(var key in regionConfs)
            {
                html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
            }
        }
        $("select[name='city_id']").html(html);
        $("select[name='city_id']").ui_select({refresh:true});
    }
</script>
<?php echo $this->fetch('jsd/user_footer.html'); ?>