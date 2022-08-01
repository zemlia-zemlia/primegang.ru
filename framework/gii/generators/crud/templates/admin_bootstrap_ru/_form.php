<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>
<?php
	function t_generateActiveField($modelClass,$column)
	{
		if($column->type==='boolean'||$column->type==='tinyint')
			return "echo \$form->checkBox(\$model,'{$column->name}')";
		elseif(stripos($column->dbType,'text')!==false)
			return "echo \$form->textArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'editor'))";
		else {
			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='passwordField';
			else
				$inputField='textField';

			if(preg_match('/^(date|datetime|timestamp)$/i',$column->name))
				return "\$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				   'name' => '{$column->name}',
				   'model' => \$model,
				   'attribute' => '{$column->name}',
				   'language' => 'ru',
				   'htmlOptions'=>array('class'=>'form-control'),
				   'options' => array('showAnim' => 'fold'),
				))";
			elseif(preg_match('/^(image|picture|imageurl|image_url)$/i',$column->name))
				return "?>

		<div class=\"image_preview\" id=\"preview_{$modelClass}_{$column->name}\">
		<?php if (!empty(\$model->{$column->name})):?>
			<img src=\"<?php echo \$model->{$column->name};?>\" class=\"img-rounded\">
		<?php else: ?>
			<img src=\"/images/admin/no-picture.png\" class=\"img-rounded\">
		<?php endif; ?>
		</div>
						
		
	    <div class=\"input-group\">
	      <span class=\"input-group-btn\">
	        <button class=\"btn btn-default\" data-toggle=\"modal\" data-target=\"#imageModal\" type=\"button\">Обзор картинок</button>
	      </span>
	      <?php echo \$form->{$inputField}(\$model,'{$column->name}',array('class'=>'form-control','data-column'=>'{$column->name}','placeholder'=>'Url картинки...')); ?>
	    </div><!-- /input-group -->
		
		<!-- Modal -->
		<div class=\"modal fade bs-example-modal-lg\" id=\"imageModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"imageModalLabel\" aria-hidden=\"true\">
		  <div class=\"modal-dialog modal-lg\">
		    <div class=\"modal-content\">
		      <div class=\"modal-header\">
		        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Закрыть\"><span aria-hidden=\"true\">&times;</span></button>
		        <h4 class=\"modal-title\" id=\"imageModalLabel\">Изображения</h4>
		      </div>
		      <div class=\"modal-body\">
		      	<iframe src=\"/filemanager/dialog.php?type=1&field_id={$modelClass}_{$column->name}\"></iframe>
		      </div>
		    </div>
		  </div>
		</div>	
				<?php echo \"\"";
			elseif($column->type!=='string' || $column->size===null)
				return "echo \$form->{$inputField}(\$model,'{$column->name}',array('class'=>'form-control','data-column'=>'{$column->name}'))";
			else
			{
				if(($size=$maxLength=$column->size)>60)
					$size=60;
				return "echo \$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength,'class'=>'form-control','data-column'=>'{$column->name}'))";
			}
		}
	}
?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="note">Поля, отмеченные <span class="required">*</span>, обязательны.</p>

	<?php echo "<?php 
		\$eS = \"\".\$form->errorSummary(\$model);
		if (!empty(\$eS)) {?>
			<div class=\"panel panel-danger\">
				<div class=\"panel-heading\">Ошибки при заполнении формы</div>
				<div class=\"panel-body\">
					<?php echo \$eS; ?>
				</div>
			</div>
		<?}	
	?>"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<div class="form-group">
		<?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php ".t_generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
	</div>

<?php
}
?>
	<div class="form-group buttons">
		<?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Создать' : 'Сохранить'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->