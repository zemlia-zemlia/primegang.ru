<div class="result">
  <div class="label" style="margin-left:0; font-weight:normal;">
    <?php echo CHtml::encode($choice->label); ?>
  </div>
  <div class="bar">
    <div class="fill" style="width: <?php echo $percent; ?>%;"></div>
  </div>
  <div class="totals">
    <span class="percent"><?php echo $percent; ?>%</span>
    <span class="votes">(<?php echo $voteCount; ?> <?php echo $voteCount == 1 ? 'голос' : 'голосов'; ?>)</span>
  </div>
</div>
