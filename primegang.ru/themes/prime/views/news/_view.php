<?php
/* @var $this NewsController */
/* @var $data News */
?>
<div class="news list item">
	<div class="date"><?php echo CHtml::encode($data->date); ?></div>
	<div class="img"><a href="/news/<?php echo $data->id; ?>-<?php echo $data->alias; ?>"><img src="<?php echo $data->image_url;?>"></a></div>
	<h1><a href="/news/<?php echo $data->id; ?>-<?php echo $data->alias; ?>"><?php echo $data->name;?></a></h1>
	<div class="text"><?php
		if(!empty($data->lead)) echo $data->lead;
		else echo $data->text; 
	?></div>
</div>
