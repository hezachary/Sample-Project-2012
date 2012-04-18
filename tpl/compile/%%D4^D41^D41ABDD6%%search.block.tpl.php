<?php /* Smarty version 2.6.26, created on 2011-12-14 15:55:13
         compiled from search.block.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'unserialize', 'search.block.tpl', 4, false),array('modifier', 'mergeString', 'search.block.tpl', 6, false),array('modifier', 'file_exists', 'search.block.tpl', 6, false),array('modifier', 'getimagesize', 'search.block.tpl', 8, false),array('modifier', 'fnBuildRewriteUrl', 'search.block.tpl', 15, false),array('modifier', 'strip_tags', 'search.block.tpl', 15, false),array('modifier', 'escape', 'search.block.tpl', 15, false),array('modifier', 'explode', 'search.block.tpl', 17, false),array('modifier', 'sizeof', 'search.block.tpl', 18, false),array('modifier', 'array_slice', 'search.block.tpl', 19, false),array('modifier', 'array_push', 'search.block.tpl', 20, false),array('modifier', 'implode', 'search.block.tpl', 25, false),array('modifier', 'replace', 'search.block.tpl', 25, false),array('modifier', 'regex_replace', 'search.block.tpl', 27, false),array('modifier', 'capitalize', 'search.block.tpl', 27, false),array('modifier', 'truncate', 'search.block.tpl', 28, false),array('function', 'math', 'search.block.tpl', 9, false),)), $this); ?>
                        
            <?php $_from = $this->_tpl_vars['ary_profile']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ary_profile_item']):
?>
                    <div class="box">
                        <?php $this->assign('entry_data', unserialize($this->_tpl_vars['ary_profile_item']['entry_data'])); ?>
                        <?php $this->assign('image_width', 172); ?>
                        <?php if (file_exists(mergeString($this->_tpl_vars['CFG']->dirroot, 'images/thumbs/', $this->_tpl_vars['entry_data']['entry_id'], '_1.jpg'))): ?>
                            <?php $this->assign('file_path', mergeString($this->_tpl_vars['CFG']->dirroot, 'images/thumbs/', $this->_tpl_vars['entry_data']['entry_id'], '_1.jpg')); ?>
                            <?php $this->assign('ary_file_size', getimagesize($this->_tpl_vars['file_path'])); ?>
                            <?php echo smarty_function_math(array('equation' => 'oh * nw / ow','ow' => $this->_tpl_vars['ary_file_size'][0],'oh' => $this->_tpl_vars['ary_file_size'][1],'nw' => $this->_tpl_vars['image_width'],'assign' => 'image_height','format' => '%u'), $this);?>

                            <?php $this->assign('image_url', mergeString($this->_tpl_vars['CFG']->www, 'images/thumbs/', $this->_tpl_vars['entry_data']['entry_id'], '_1.jpg')); ?>
                        <?php else: ?>
                            <?php $this->assign('image_height', '160'); ?>
                            <?php $this->assign('image_url', mergeString($this->_tpl_vars['CFG']->www, 'images/sample_thumb.jpg')); ?>
                        <?php endif; ?>
                        <a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
detail/<?php echo fnBuildRewriteUrl($this->_tpl_vars['ary_valid_input'], 'id', $this->_tpl_vars['ary_profile_item']['id']); ?>
"><img src="<?php echo $this->_tpl_vars['image_url']; ?>
" width="<?php echo $this->_tpl_vars['image_width']; ?>
" height="<?php echo $this->_tpl_vars['image_height']; ?>
" alt="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ary_profile_item']['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall') : smarty_modifier_escape($_tmp, 'htmlall')); ?>
" /><span class="file_type <?php echo $this->_tpl_vars['ary_profile_item']['file_type']; ?>
"><span class="accessibility"><?php echo $this->_tpl_vars['ary_profile_item']['file_type']; ?>
</span></span></a>
                        <p class="award <?php echo $this->_tpl_vars['ary_profile_item']['award']; ?>
" title="<?php echo $this->_tpl_vars['ary_profile_item']['award']; ?>
"><span><?php echo $this->_tpl_vars['ary_profile_item']['award']; ?>
</span></p>
                        <?php $this->assign('ary_name_list', explode(';', $this->_tpl_vars['ary_profile_item']['user_name'])); ?>
                        <?php if (sizeof($this->_tpl_vars['ary_name_list']) > 4): ?>
                            <?php $this->assign('ary_name_list', array_slice($this->_tpl_vars['ary_name_list'], 0, 3)); ?>
                            <?php $this->assign('tmp', array_push($this->_tpl_vars['ary_name_list'], '...')); ?>
                        <?php else: ?>
                            <?php $this->assign('ary_name_list', array_slice($this->_tpl_vars['ary_name_list'], 0, 4)); ?>
                        <?php endif; ?>
                        
                        <h2 class="member_list"><a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
detail/<?php echo fnBuildRewriteUrl($this->_tpl_vars['ary_valid_input'], 'id', $this->_tpl_vars['ary_profile_item']['id']); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=implode('<br/>', $this->_tpl_vars['ary_name_list']))) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, '###', ';') : smarty_modifier_replace($_tmp, '###', ';')); ?>
</a></h2>
                        <div class="clear"><br class="accessibility"/></div>
                        <p class="subject"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['entry_data']['import']['Category'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/^\([^\)]+\)/', '') : smarty_modifier_regex_replace($_tmp, '/^\([^\)]+\)/', '')))) ? $this->_run_mod_handler('replace', true, $_tmp, '_', ' ') : smarty_modifier_replace($_tmp, '_', ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')))) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</p>
                        <p class="desc"><small><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ary_profile_item']['entry_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, '...') : smarty_modifier_truncate($_tmp, 30, '...')); ?>
</small></p>
                                            </div>
            <?php endforeach; endif; unset($_from); ?>