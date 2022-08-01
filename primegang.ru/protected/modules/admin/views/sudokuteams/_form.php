<?php
/* @var $this SudokuteamsController */
/* @var $model SudokuTeams */
/* @var $form CActiveForm */
?>

<!-- relation scripts -->
<script>
	$(function() {
		$('div[data-actiongroup=manageplayers] button').click(function() {
			var _element	 = $(this);
			var _action		 = _element.attr('data-action');
			var _class		 = _element.attr('data-class');
			var _idplayer	 = _element.find("input[name=id_player]").val();
			
			_url = "/admin/sudokuteams/manageplayer";
			_data = {'Manageplayer':{'action':_action,'id':_idplayer}};
			
			$.ajax({
				'type'		:'POST',
				'url'		:_url,
				'data'		:_data,
				'success'	:function(data) {
					var _tr = _element.parents('tr');
					_tr.find('td.position').html(data);
					_tr.find('td.position').addClass('change');
					_tr.attr("class","");
					_tr.addClass(_class);
				}
			}); 
		});		
	});
</script>
<style>
	td.position.change {background:#9CE29C;}
	tr td.buttons button {display:none;}
	tr.captain		td.buttons button.fireplayer {display:inline-block;}
	tr.vicecaptain	td.buttons button.fireplayer {display:inline-block;}
	tr.player	 	td.buttons button.setcaptain {display:inline-block;}
	tr.player	 	td.buttons button.setvicecaptain {display:inline-block;}
</style>
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sudoku-teams-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span>, обязательны.</p>

	<?php 
		$eS = "".$form->errorSummary($model);
		if (!empty($eS)) {?>
			<div class="panel panel-danger">
				<div class="panel-heading">Ошибки при заполнении формы</div>
				<div class="panel-body">
					<?php echo $eS; ?>
				</div>
			</div>
		<?}	
	?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'name')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'alias')); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->checkbox($model,'active',array('data-column'=>'active')); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php ?>

		<div class="image_preview" id="preview_SudokuTeams_image_url">
		<?php if (!empty($model->image_url)):?>
			<img src="<?php echo $model->image_url;?>" class="img-rounded">
		<?php else: ?>
			<img src="/images/admin/no-picture.png" class="img-rounded">
		<?php endif; ?>
		</div>
						
		
	    <div class="input-group">
	      <span class="input-group-btn">
	        <button class="btn btn-default" data-toggle="modal" data-target="#imageModal" type="button">Обзор картинок</button>
	      </span>
	      <?php echo $form->textField($model,'image_url',array('class'=>'form-control','data-column'=>'image_url','placeholder'=>'Url картинки...')); ?>
	    </div><!-- /input-group -->
		
		<!-- Modal -->
		<div class="modal fade bs-example-modal-lg" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="imageModalLabel">Изображения</h4>
		      </div>
		      <div class="modal-body">
		      	<iframe src="/filemanager/dialog.php?type=1&field_id=SudokuTeams_image_url"></iframe>
		      </div>
		    </div>
		  </div>
		</div>	
				<?php echo ""; ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>



	<div class="well">
		<h3 style="margin-top:0px;">Игроки</h3>
		<table class="table">
		<?php foreach($model->players as $player):
		?>
			<tr class="<?php if($player->captain):?>captain<?php elseif($player->vicecaptain):?>vicecaptain<?php else:?>player<?php endif;?>">
				<td class="position"><?php if($player->captain):?>Капитан<?php elseif($player->vicecaptain):?>Вице-капитан<?php else:?>Игрок<?php endif;?></td>
				<td class="name"	><?php echo $player->name; ?></td>
				<td class="username"><?php echo (!empty($player->user)) ? $player->user->display_name : "Удален";?></td>
				<td class="buttons">
					<div class="btn-group" role="group" aria-label="Управление игроками" data-actiongroup="manageplayers">
						<button type="button" class="btn btn-default fireplayer" data-action="fireplayer" data-class="player">
							<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
							Снять капитанство</button>
						<button type="button" class="btn btn-default setcaptain" data-action="setcaptain" data-class="captain">
							<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
							Капитан</button>
						<button type="button" class="btn btn-default setvicecaptain" data-action="setvicecaptain" data-class="vicecaptain">
							<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
							Вице-капитан</button>
					</div>					
					
				</td>
			</tr>
		<?php endforeach;?>
		</table>
	</div>


	<div class="form-group">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'editor-mini')); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>
	
	<div class="form-group buttons">
		<button class="btn btn-primary" type="submit"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить'); ?></button>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

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