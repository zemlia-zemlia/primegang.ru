<style>
	.option label {font-weight:normal; margin:0;}
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'portlet-poll-form',
  'enableAjaxValidation'=>false,
)); ?>

  <?php echo $form->errorSummary($model); ?>
	
  <div class="options">
    <?php $template = '<div class="option">{input}{label}</div>'; ?>
    <?php echo $form->radioButtonList($userVote,'choice_id',$choices,array(
      'template'=>$template,
      'separator'=>'',
      'name'=>'PortletPollVote_choice_id')); ?>
    <?php echo $form->error($userVote,'choice_id'); ?>
  </div>
	<div class="button"><button type="submit" name="yt0" class="btn btn-primary">Голосовать</button></div>  	

<?php $this->endWidget(); ?>

</div><!-- form -->
