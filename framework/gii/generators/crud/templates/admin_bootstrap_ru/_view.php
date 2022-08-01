<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $data <?php echo $this->getModelClass(); ?> */
?>

<div class="view panel panel-default">
	<div class="panel-heading"><?php 
			$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
			echo "\t<?php echo CHtml::link(CHtml::encode(\$data->{$nameColumn}), array('update', 'id'=>\$data->{$this->tableSchema->primaryKey})); ?>"; 
	?></div>
	<div class="panel-body">
		<?php
			if(isset($this->tableSchema->columns['image_url'])) {
				$image = $this->tableSchema->columns['image_url'];
			?>
				<div class="image_preview"><img src="<?php echo "<?php echo \$data->{$image->name};?>";?>" class="img-rounded"></div>
			<?}
		?>
		<?php
			$count=0;
			foreach($this->tableSchema->columns as $column)
			{
				if($column->isPrimaryKey)
					continue;
				if($column->name == 'image_url') 
					continue;
				if(++$count==7)
					echo "\t<?php /*\n";
				echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
				if (in_array($column->name, array('text','body','description','lead')))
					echo "\t<?php echo CommonFunctions::truncateText(\$data->{$column->name}); ?>\n\t<br />\n\n";
				else 
					echo "\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
			}
			if($count>=7)
				echo "\t*/ ?>\n";
			?>	
	</div>
</div>