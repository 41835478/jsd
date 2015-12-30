<?php echo $this->fetch('jsd/global_header.html'); ?>
<section class="order_data_box">
    <section class="top_data">
        <img src="<?php echo $this->_var['APP_ROOT']; ?>/public/jsd/images/bj_3.png">
        <ul>
            <li class="return"><a href="javascript:void(0);" onclick="javascript:history.back(-1);">返回</a> </li>
            <li class="phone"><i><?php echo $this->_var['jsd_user_name']; ?></i></li>
        </ul>
    </section>