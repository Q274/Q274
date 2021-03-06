<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_postinstall
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$lang     = Factory::getLanguage();
$renderer = Factory::getDocument()->loadRenderer('module');
$options  = array('style' => 'raw');
$mod      = JModuleHelper::getModule('mod_feed');
$param    = array(
	'rssurl'      => 'https://www.joomla.org/announcements/release-news.feed?type=rss',
	'rsstitle'    => 0,
	'rssdesc'     => 0,
	'rssimage'    => 1,
	'rssitems'    => 5,
	'rssitemdesc' => 1,
	'rssitemdate' => 1,
	'rssrtl'      => $lang->isRtl() ? 1 : 0,
	'word_count'  => 200,
	'cache'       => 0,
	);
$params = array('params' => json_encode($param));

JHtml::_('formbehavior.chosen', 'select');
?>

<form action="index.php" method="post" name="adminForm" class="form-inline" id="adminForm">
	<input type="hidden" name="option" value="com_postinstall">
	<input type="hidden" name="task" value="">
	<?php echo JHtml::_('form.token'); ?>
	<label for="eid"><?php echo JText::_('COM_POSTINSTALL_MESSAGES_FOR'); ?></label>
	<?php echo JHtml::_('select.genericlist', $this->extension_options, 'eid', array('onchange' => 'this.form.submit()', 'class' => 'input-xlarge'), 'value', 'text', $this->eid, 'eid'); ?>
</form>

<?php if ($this->eid == 700) : ?>
<div class="row-fluid">
	<div class="span8">
<?php endif; ?>
		<?php if (empty($this->items)) : ?>
			<div class="hero-unit">
				<h2><?php echo JText::_('COM_POSTINSTALL_LBL_NOMESSAGES_TITLE'); ?></h2>
				<p><?php echo JText::_('COM_POSTINSTALL_LBL_NOMESSAGES_DESC'); ?></p>
				<a href="index.php?option=com_postinstall&amp;view=messages&amp;task=reset&amp;eid=<?php echo $this->eid; ?>&amp;<?php echo $this->token; ?>=1" class="btn btn-warning btn-large">
					<span class="icon icon-eye-open" aria-hidden="true"></span>
					<?php echo JText::_('COM_POSTINSTALL_BTN_RESET'); ?>
				</a>
			</div>
		<?php else : ?>
			<?php foreach ($this->items as $item) : ?>
			<fieldset>
				<legend><?php echo JText::_($item->title_key); ?></legend>
				<p class="small">
					<?php echo JText::sprintf('COM_POSTINSTALL_LBL_SINCEVERSION', $item->version_introduced); ?>
				</p>
				<div>
					<?php echo JText::_($item->description_key); ?>
					<?php if ($item->type !== 'message') : ?>
					<a href="index.php?option=com_postinstall&amp;view=messages&amp;task=action&amp;id=<?php echo $item->postinstall_message_id; ?>&amp;<?php echo $this->token; ?>=1" class="btn btn-primary">
						<?php echo JText::_($item->action_key); ?>
					</a>
					<?php endif; ?>
					<?php if (Factory::getUser()->authorise('core.edit.state', 'com_postinstall')) : ?>
					<a href="index.php?option=com_postinstall&amp;view=message&amp;task=unpublish&amp;id=<?php echo $item->postinstall_message_id; ?>&amp;<?php echo $this->token; ?>=1" class="btn btn-inverse btn-small">
						<?php echo JText::_('COM_POSTINSTALL_BTN_HIDE'); ?>
					</a>
					<?php endif; ?>
				</div>
			</fieldset>
			<?php endforeach; ?>
		<?php endif; ?>
<?php if ($this->eid == 700) : ?>
	</div>
	<div class="span4"<?php if ($lang->isRtl()) : ?> style="padding-right: 20px;"<?php endif; ?>>
		<h2><?php echo JText::_('COM_POSTINSTALL_LBL_RELEASENEWS'); ?></h2>
		<?php echo $renderer->render($mod, $params, $options); ?>
	</div>
</div>
<?php endif; ?>
