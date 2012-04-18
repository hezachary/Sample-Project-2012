<?php /* Smarty version 2.6.26, created on 2011-12-14 16:10:56
         compiled from page.home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fnRemoveElement', 'page.home.tpl', 3, false),array('modifier', 'fnBuildRewriteUrl', 'page.home.tpl', 17, false),)), $this); ?>

<script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
js/page_search.js"></script>
<?php $this->assign('ary_valid_input_for_view', fnRemoveElement($this->_tpl_vars['ary_valid_input'], 'award')); ?>
<?php $this->assign('selected_view', 'All Entries'); ?>
<?php $_from = $this->_tpl_vars['ary_entry_view_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['str_view_value'] => $this->_tpl_vars['str_entry_view']):
?>
    <?php if ($this->_tpl_vars['ary_valid_input']['award'] == $this->_tpl_vars['str_view_value']): ?>
        <?php $this->assign('selected_view', $this->_tpl_vars['str_entry_view']); ?>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<div class="top_bar">
    <div class="left"><span class="counts"><span class="inner"><?php echo $this->_tpl_vars['int_total']; ?>
</span></span><span class="desc">Results</span><span class="clear"><br class="accessibility"/></span></div>
    <div class="right">
        <div class="panel panel_filter">
            <a class="subject" href="javascript:void(0)" onclick="yg_search.expandPanel(this)"><strong>View By :</strong> <span><?php echo $this->_tpl_vars['selected_view']; ?>
</span></a>
            <ul class="sub_panel">
                <?php if ($this->_tpl_vars['ary_valid_input']['award']): ?>
                <li><a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
page/<?php echo fnBuildRewriteUrl($this->_tpl_vars['ary_valid_input_for_view']); ?>
">All Entries</a></li>
                <?php else: ?>
                <li><span>All Entries</span></li>
                <?php endif; ?>
                <?php $_from = $this->_tpl_vars['ary_entry_view_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['str_view_value'] => $this->_tpl_vars['str_entry_view']):
?>
                <?php if ($this->_tpl_vars['ary_valid_input']['award'] == $this->_tpl_vars['str_view_value']): ?>
                <li><span><?php echo $this->_tpl_vars['str_entry_view']; ?>
</span></li>
                <?php else: ?>
                <li><a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
page/<?php echo fnBuildRewriteUrl($this->_tpl_vars['ary_valid_input_for_view'], 'award', $this->_tpl_vars['str_view_value']); ?>
"><?php echo $this->_tpl_vars['str_entry_view']; ?>
</a></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
            </ul>
        </div>
    </div>
    <div class="clear"><br class="accessibility" /></div>
</div>
<div id="container"><?php if (! $this->_tpl_vars['int_total'] && $this->_tpl_vars['ary_valid_input']['search_field']): ?><p class="no_results">Nothing found - try again</p><?php endif; ?></div>
<div class="overlay" id="welcome">
    <div class="details">
		<h2>Congratulations to the all the young talent whose work is in this showcase.</h2>
		<p>To be awarded a Bullet signifies that you are talent on the rise; a leader of tomorrow or even a potential creative demi-god. This recognition means that you will “Become the Hunted” for your talent, and you deserve it.</p>
        <p>Enjoy the great work and ideas in here; and take away some inspiration.<br />If your name isn’t here yet, don’t give up trying.</p>
		<div class="hr"><hr /></div>
        <div class="left">
            <h3>2011 Professional YoungGuns of the Year:</h3>
            <p> Alexander Nowak &amp; Felix Richter<br /><br />
                Y&amp;R NewYork <br />
                Airwalk <br />
                United States</p>
        </div>
        <div class="right">
            <h3>2011 Student YoungGun of the Year:</h3>
            <p> Edi Inderbitzin , Maximilian Gebhardt &amp; Maximilian Hoch<br />
                Miami Ad School Europe<br />
                Kinder<br />
                Germany
            </p>
        </div>
        <div class="hr"><hr /></div>
        <h4>YoungGuns also recognizes the organizations that incubate, support and develop young talent:</h4>
        <div class="left">
            <h4>2011 Advertising Agency of the Year:</h4>
            <p>Leo Burnett Chicago</p>
            <h4>2011 Digital Agency of the Year:</h4>
            <p>Y&amp;R, New York</p>
            <h4>2011 Design Agency of Year:</h4>
            <p>DDB Philippines</p>
        </div>
        <div class="right">
            <h4>2011 Network of the Year:</h4>
            <p>Leo Burnett Worldwide</p>
            <h4>2011 School of the Year:  :</h4>
            <p>Miami AdSchool Europe</p>
        </div>
        <div class="hr"><hr /></div>
        <p class="extra">Before you get into the work - to get all the 2011 YoungGuns Award information download the <a class="pdf" href="javascript:void(0)" onclick="jq('#welcome').data('overlay').close();yg_tools.loadOverlay()">2011 Winners &amp; Finalists PDFs <img src="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
images/pdf.gif" alt="2011 Winners &amp; Finalists PDFs" /></a><br/>
        Remember almost everything great is done by youth.</p>
	</div>
</div>