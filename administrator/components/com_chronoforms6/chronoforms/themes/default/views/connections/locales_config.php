<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab locale-tab <?php if($name == \G2\L\Config::get('site.language')): ?>active<?php endif; ?>" data-tab="locale-<?php echo $name; ?>">
	
	<div class="two fields">
		<div class="field">
			<label><?php el('Name'); ?></label>
			<input type="text" value="<?php echo $name; ?>" name="Connection[locales][<?php echo $name; ?>][name]" readonly="true">
		</div>
	</div>
	
	<div class="field">
		<label><?php el('Content'); ?></label>
		<textarea placeholder="<?php el('Multiline list of locale_string=translation'); ?>" name="Connection[locales][<?php echo $name; ?>][content]" rows="20"></textarea>
		<small><?php el('Multiline list of strings and their translations, e.g: _STRING_=translation, translations can be called in the app using {l:_STRING_}'); ?></small>
	</div>
	
</div>