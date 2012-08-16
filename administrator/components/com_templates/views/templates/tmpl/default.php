<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_templates&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
	<!-- Begin Sidebar -->
	<div id="sidebar" class="span2">
		<div class="sidebar-nav">
			<?php
				// Display the submenu position modules
				$this->modules = JModuleHelper::getModules('submenu');
				foreach ($this->modules as $module) {
					$output = JModuleHelper::renderModule($module);
					$params = new JRegistry;
					$params->loadString($module->params);
					echo $output;
				}
			?>
			<hr />
			<div class="filter-select">
				<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
				<select name="filter_client_id" class="span12 small" onchange="this.form.submit()">
					<option value="*"><?php echo JText::_('JGLOBAL_FILTER_CLIENT'); ?></option>
					<?php echo JHtml::_('select.options', TemplatesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
				</select>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
	<!-- Begin Content -->
	<div class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_TEMPLATES_TEMPLATES_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_TEMPLATES_TEMPLATES_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="template-mgr">
			<thead>
				<tr>
					<th class="col1template">
						&#160;
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.element', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'JCLIENT', 'a.client_id', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JText::_('JVERSION'); ?>
					</th>
					<th width="15%">
						<?php echo JText::_('JDATE'); ?>
					</th>
					<th width="25%" >
						<?php echo JText::_('JAUTHOR'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('templates.thumb', $item->element, $item->client_id); ?>
					</td>
					<td class="template-name">
						<a href="<?php echo JRoute::_('index.php?option=com_templates&view=template&id='.(int) $item->extension_id); ?>">
							<?php echo JText::sprintf('COM_TEMPLATES_TEMPLATE_DETAILS', ucfirst($item->name)); ?></a>
						<p>
						<?php if($this->preview && $item->client_id == '0'): ?>
							<a href="<?php echo JURI::root().'index.php?tp=1&template='.$item->element; ?>" target="_blank">
								<?php echo  JText::sprintf('COM_TEMPLATES_TEMPLATE_PREVIEW'); ?></a>
						<?php elseif ($item->client_id == '1'): ?>
							<?php echo  JText::sprintf('COM_TEMPLATES_TEMPLATE_NO_PREVIEW_ADMIN'); ?>
						<?php else: ?>
							<span class="hasTip" title="<?php echo JText::sprintf('COM_TEMPLATES_TEMPLATE_NO_PREVIEW'); ?>::<?php echo JText::sprintf('COM_TEMPLATES_TEMPLATE_NO_PREVIEW_DESC'); ?>">
								<?php echo  JText::sprintf('COM_TEMPLATES_TEMPLATE_NO_PREVIEW'); ?></span>
						<?php endif; ?>
						</p>
					</td>
					<td class="small">
						<?php echo $item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>
					</td>
					<td class="small">
						<?php echo $this->escape($item->xmldata->get('version')); ?>
					</td>
					<td class="small">
						<?php echo $this->escape($item->xmldata->get('creationDate')); ?>
					</td>
					<td>
						<?php if ($author = $item->xmldata->get('author')) : ?>
							<p><?php echo $this->escape($author); ?></p>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
						<?php if ($email = $item->xmldata->get('authorEmail')) : ?>
							<p><?php echo $this->escape($email); ?></p>
						<?php endif; ?>
						<?php if ($url = $item->xmldata->get('authorUrl')) : ?>
							<p><a href="<?php echo $this->escape($url); ?>">
								<?php echo $this->escape($url); ?></a></p>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		</div>
		<!-- End Content -->
	</div>
</form>