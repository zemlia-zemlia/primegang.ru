<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile"),
);
$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
    array('label'=>UserModule::t('Edit'), 'url'=>array('edit')),
    array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">

			<h1><?php echo UserModule::t('Your profile'); ?></h1>
			
			<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
			<div class="success">
				<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
			</div>
			<?php endif; ?>
			<table class="dataGrid">
				<tr>
					<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
				    <td><?php echo CHtml::encode($model->username); ?></td>
				</tr>
				<?php 
					$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
					if ($profileFields) {
						foreach($profileFields as $field) {
							//echo "<pre>"; print_r($profile); die();
						?>
				<tr>
					<th class="label"><?php echo CHtml::encode(UserModule::t($field->title)); ?></th>
			    	<td><?php 
			    		if($field->varname == 'userimage'):?>
			    		<img class="userimage" src="/<?php 
				    		$src = "".$profile->getAttribute($field->varname);
				    		if(!empty($src)) echo $src;
							else echo "images/site/no_avatar.png";
			    		?>">
			    		<? else:
			    		echo (($field->widgetView($profile))?$field->widgetView($profile):CHtml::encode((($field->range)?Profile::range($field->range,$profile->getAttribute($field->varname)):$profile->getAttribute($field->varname))));
						endif; 
			    		?></td>
				</tr>
						<?php
						}//$profile->getAttribute($field->varname)
					}
				?>
				<tr>
					<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></th>
			    	<td><?php echo CHtml::encode($model->email); ?></td>
				</tr>
				<tr>
					<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></th>
			    	<td><?php echo $model->create_at; ?></td>
				</tr>
				<tr style="display:none;">
					<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?></th>
			    	<td><?php echo $model->lastvisit_at; ?></td>
				</tr>
				<tr>
					<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></th>
			    	<td><?php echo CHtml::encode(User::itemAlias("UserStatus",$model->status)); ?></td>
				</tr>
			</table>
			
			<div class="btn-group" role="group" aria-label="...">
				<a role="button" class="btn btn-default" href="/user/profile/edit">Редактировать</a>
				<a role="button" class="btn btn-default" href="/user/profile/changepassword">Сменить пароль</a>
				<a role="button" class="btn btn-default" href="/user/profile/logout">Выйти</a>
			</div>
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