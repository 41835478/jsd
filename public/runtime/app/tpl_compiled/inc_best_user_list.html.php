	<?php $_from = $this->_var['uc_u_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'u_item');if (count($_from)):
    foreach ($_from AS $this->_var['u_item']):
?>
		<div class="u_item">
			<div class="avatar"><?php 
$k = array (
  'name' => 'show_avatar',
  'uid' => $this->_var['u_item']['id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?></div>
			<div class="u_info">
				<p><span class="u_name"><?php echo $this->_var['u_item']['user_name']; ?></span></p>
				<p>
					<?php if ($this->_var['u_item']['focused'] == 1): ?>
							<a href="javascript:;" onclick="focus_user(<?php echo $this->_var['u_item']['id']; ?>,this);" class="remove_focus" style="margin:0px 5px; _margin-top:-1px;">取消关注</a>
					<?php else: ?>
							<a href="javascript:;" onclick="focus_user(<?php echo $this->_var['u_item']['id']; ?>,this);" class="add_focus"  style="margin:0px 5px;  _margin-top:-1px;">加关注</a>
					<?php endif; ?>
				</p>
			</div>
		</div>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<div class="blank10"></div>