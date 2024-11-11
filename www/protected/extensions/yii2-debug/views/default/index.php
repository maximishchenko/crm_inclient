<?php
/**
 * @var DefaultController $this
 * @var array $manifest
 */

$this->pageTitle = 'Available Debug Data - Yii Debugger';
?>
<div class="default-index">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">
				<div class="yii2-debug-toolbar-block title">
					Yii Debugger
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<h1>Available Debug Data</h1>
			<table class="table table-condensed table-bordered table-striped table-hover table-filtered" style="table-layout: fixed;">
				<thead>
				<tr>
					<th style="width:20px;text-align:center;"><i class="icon-star"></i></th>
					<th style="width: 160px;">Time</th>
					<th style="width: 120px;">IP</th>
					<th style="width: 60px;">Method</th>
					<th style="width: 40px;">Code</th>
					<th>URL</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($manifest as $tag => $data): ?>
					<tr>
						<td style="text-align:center;">
							<?php echo CHtml::link(
								'<i class="icon-star' . (!$this->owner->getLock($tag) ? '-empty' : '') . '"></i>',
								array('lock', 'tag' => $tag),
								array(
									'class' => 'lock' . ($this->owner->getLock($tag) ? ' active' : ''),
									'title' => 'Lock or unlock of deleting',
								)
							); ?>
						</td>
						<td><?php echo CHtml::link(date('Y-m-d h:i:s', $data['time']), array('view', 'tag' => $tag)); ?></td>
						<td><?php echo $data['ip']; ?></td>
						<td><?php echo $data['method']; ?></td>
						<td style="text-align:center;"><?php echo isset($data['code']) ? Yii2RequestPanel::getStatusCodeHtml($data['code']) : ''; ?></td>
						<td style="word-break:break-all;">
							<?php echo CHtml::encode(urldecode($data['url'])); ?>
							<?php echo CHtml::link('<i class="icon-share"></i>', $data['url'], array('class' => 'share', 'target' => 'blank')); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>