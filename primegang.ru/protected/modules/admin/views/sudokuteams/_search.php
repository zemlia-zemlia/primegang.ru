<?php
/* @var $this SudokuteamsController */
/* @var $model SudokuTeams */
/* @var $form CActiveForm */
?>



<!-- relation scripts -->



<div class="wide form search-form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<div class="form-group">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'name')); ?>
		</div>
		<div class="form-group">
			<?php echo $form->label($model,'active'); ?>
			<?php echo $form->checkbox($model,'active',array('data-column'=>'active')); ?>
		</div>
	
	<div class="row buttons">
		<button class="btn btn-primary" type="submit">Искать</button>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->

<!-- Modal -->
<div class="modal fade" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="selectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Выберите значение</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>