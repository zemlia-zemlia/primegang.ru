<?php
$this->breadcrumbs=array(
  'Опросы'=>array('index'),
  $model->title,
);

$this->menu=array(
  array('label'=>'Список опросов', 'url'=>array('index')),
  array('label'=>'Создать опрос', 'url'=>array('create')),
  array('label'=>'Редактировать опрос', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Экспорт', 'url'=>array('export', 'id'=>$model->id)),
  array('label'=>'Удалить опрос', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Хотите удалить этот опрос?')),
);
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<?php if ($model->description): ?>
<p class="description"><?php echo CHtml::encode($model->description); ?></p>
<?php endif; ?>

<?php $this->renderPartial('_results', array('model' => $model)); ?>

<?php if ($userVote->id): ?>
  <p id="pollvote-<?php echo $userVote->id ?>">
    Вы проголосовали: <strong><?php echo $userChoice->label ?></strong>.<br />
    <?php
      if ($userCanCancel) {
        echo CHtml::ajaxLink(
          'Отменить голос',
          array('/poll/pollvote/delete', 'id' => $userVote->id, 'ajax' => TRUE),
          array(
            'type' => 'POST',
            'success' => 'js:function(){window.location.reload();}',
          ),
          array(
            'class' => 'cancel-vote',
            'confirm' => 'Хотите отменить свой голос?'
          )
        );
      }
    ?>
  </p>
<?php else: ?>
  <p><?php echo CHtml::link('Голосовать', array('/poll/poll/vote', 'id' => $model->id)); ?></p>
<?php endif; ?>
