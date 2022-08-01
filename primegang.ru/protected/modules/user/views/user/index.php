<?php
$this->breadcrumbs=array(
	UserModule::t("Users"),
);
if(false && UserModule::isAdmin()) {
	$this->layout='//layouts/column2';
	$this->menu=array(
	    array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin')),
	    array('label'=>UserModule::t('Manage Profile Field'), 'url'=>array('profileField/admin')),
	);
}
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<h1><?php echo UserModule::t("List User"); ?></h1>
			
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider'=>$dataProvider,
				'columns'=>array(
					array(
						'name' => 'username',
						'type'=>'raw',
						'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
					),
					'create_at',
				),
			)); ?>
		</div>			
		<div class="col-lg-6 right">
			<div class="sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"season",
					'dataId'=> "",
					'limit' => 3,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		</div>
	</div>
</div>	