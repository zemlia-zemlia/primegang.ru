<?php $this->render('results', array('model' => $model)); ?>

<?php if ($userVote->id): ?>
  <p id="pollvote-<?php echo $userVote->id ?>">
    Вы проголосовали: <?php echo $userChoice->label ?>.<br />
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
            'confirm' => 'Хотите отменить голос?'
          )
        );
      }
    ?>
  </p>
<?php else: ?>
  <p><?php echo CHtml::link('Голосовать', array('/poll/poll/vote', 'id' => $model->id)); ?></p>
<?php endif; ?>
