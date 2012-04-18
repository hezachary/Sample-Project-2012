<?php /* Smarty version 2.6.26, created on 2011-12-14 14:45:30
         compiled from search.detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fnRetrieveFileName', 'search.detail.tpl', 5, false),array('modifier', 'replace', 'search.detail.tpl', 16, false),array('modifier', 'substr', 'search.detail.tpl', 32, false),)), $this); ?>

            <div class="photo-info main" id="photo_info$<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
$<?php if ($this->_tpl_vars['file_type'] == 'url'): ?><?php echo $this->_tpl_vars['ary_file']['entry_url_id']; ?>
<?php else: ?><?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
<?php endif; ?>">

                    <?php if ($this->_tpl_vars['file_type'] == 'flv' || $this->_tpl_vars['file_type'] == 'avi' || $this->_tpl_vars['file_type'] == 'mov' || $this->_tpl_vars['file_type'] == 'mp4' || $this->_tpl_vars['file_type'] == 'mpg' || $this->_tpl_vars['file_type'] == 'mpeg' || $this->_tpl_vars['file_type'] == 'wmv'): ?>
                       <?php $this->assign('view_file_name', ((is_array($_tmp=$this->_tpl_vars['ary_file']['entry_id'])) ? $this->_run_mod_handler('fnRetrieveFileName', true, $_tmp, $this->_tpl_vars['ary_file']['name'], 'video') : fnRetrieveFileName($_tmp, $this->_tpl_vars['ary_file']['name'], 'video'))); ?>
                    <?php elseif ($this->_tpl_vars['file_type'] == 'gif' || $this->_tpl_vars['file_type'] == 'jpg' || $this->_tpl_vars['file_type'] == 'jpeg' || $this->_tpl_vars['file_type'] == 'png'): ?>
                        <?php $this->assign('view_file_name', ((is_array($_tmp=$this->_tpl_vars['ary_file']['entry_id'])) ? $this->_run_mod_handler('fnRetrieveFileName', true, $_tmp, $this->_tpl_vars['ary_file']['name'], 'web') : fnRetrieveFileName($_tmp, $this->_tpl_vars['ary_file']['name'], 'web'))); ?>
                    <?php else: ?>
					    <?php $this->assign('view_file_name', $this->_tpl_vars['ary_file']['name']); ?>
                    <?php endif; ?>
                    <div align="center">
					<?php if ($this->_tpl_vars['file_type'] == 'gif' || $this->_tpl_vars['file_type'] == 'jpg' || $this->_tpl_vars['file_type'] == 'jpeg' || $this->_tpl_vars['file_type'] == 'png'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center"><img src="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
images/entries/<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
_1.jpg" alt="<?php echo $this->_tpl_vars['file_type']; ?>
" width="685" /></p>
					<?php elseif ($this->_tpl_vars['file_type'] == 'flv' || $this->_tpl_vars['file_type'] == 'avi' || $this->_tpl_vars['file_type'] == 'mov' || $this->_tpl_vars['file_type'] == 'mp4' || $this->_tpl_vars['file_type'] == 'mpg' || $this->_tpl_vars['file_type'] == 'mpeg' || $this->_tpl_vars['file_type'] == 'wmv'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center"></p>
                        <script>yg_tools.loadVideo('<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['ary_file']['location'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https:', 'http:') : smarty_modifier_replace($_tmp, 'https:', 'http:')); ?>
/<?php echo $this->_tpl_vars['view_file_name']; ?>
', 685, 514, '<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
images/entries/<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
_1.jpg');</script>
					<?php elseif ($this->_tpl_vars['file_type'] == 'wav'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center"></p>
                        <script>yg_tools.loadAudio('<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['ary_file']['location'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https:', 'http:') : smarty_modifier_replace($_tmp, 'https:', 'http:')); ?>
/<?php echo $this->_tpl_vars['view_file_name']; ?>
', 685, 24);</script>
					<?php elseif ($this->_tpl_vars['file_type'] == 'mp3' || $this->_tpl_vars['file_type'] == 'wma'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center"></p>
                        <script>yg_tools.loadAudio('<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['ary_file']['location'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https:', 'http:') : smarty_modifier_replace($_tmp, 'https:', 'http:')); ?>
/<?php echo $this->_tpl_vars['view_file_name']; ?>
', 685, 70);</script>
					<?php elseif ($this->_tpl_vars['file_type'] == 'swf'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center"></p>
                        <script>yg_tools.loadFlash('<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['ary_file']['location'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https:', 'http:') : smarty_modifier_replace($_tmp, 'https:', 'http:')); ?>
/<?php echo $this->_tpl_vars['view_file_name']; ?>
', 685, 514);</script>
					<?php elseif ($this->_tpl_vars['file_type'] == 'doc' || $this->_tpl_vars['file_type'] == 'docx' || $this->_tpl_vars['file_type'] == 'txt' || $this->_tpl_vars['file_type'] == 'pdf'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_file_id']; ?>
" align="center">
                            <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ary_file']['location'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https:', 'http:') : smarty_modifier_replace($_tmp, 'https:', 'http:')); ?>
/<?php echo $this->_tpl_vars['view_file_name']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
images/pdf_icon.jpg" alt="Please use your mouse right Click here and Choose [Save Link As ...]" border="0" /></a>
                        </p>
					<?php elseif ($this->_tpl_vars['file_type'] == 'url'): ?>
                        <p id="entry_file_<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_url_id']; ?>
" align="center">
                            <a href="<?php if (substr($this->_tpl_vars['view_file_name'], 0, 4) == 'http'): ?><?php echo $this->_tpl_vars['view_file_name']; ?>
<?php else: ?>http://<?php echo $this->_tpl_vars['view_file_name']; ?>
<?php endif; ?>" target="_blank"><img width="685" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
images/entries/<?php echo $this->_tpl_vars['ary_file']['entry_id']; ?>
_<?php echo $this->_tpl_vars['ary_file']['entry_url_id']; ?>
_1.jpg" border="0" /></a>
                        </p>
					<?php endif; ?>
                    </div>
            </div>
            <div class="gap"><hr class="accessibility" /></div>
            