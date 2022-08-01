<?php
$this->pageTitle = 'Клуб футбольных прогнозистов';
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<?php $this -> widget( 'zii.widgets.CListView', array (
			        'dataProvider' =>$newsProvider ,
			        'itemView' =>'_newsView' ,
			        'template' =>"{items} \n <nav>{pager}</nav>" ,
			        'pagerCssClass' => 'cpager',
			        'pager' => array (
			       'firstPageLabel' =>'&laquo;' ,
			       'prevPageLabel' =>'&lt;' ,
			       'nextPageLabel' =>'&gt;' ,
			       'lastPageLabel' =>'&raquo;' ,
			       'selectedPageCssClass' => 'active',
			       'maxButtonCount' =>'8' ,
			       'header' =>'' ,
			       'htmlOptions' =>array ( 'class'=> 'pagination' )
			   ),                
			)); ?>
		</div>
		<div class="col-lg-6 right">
			<?php $this->widget('Statistics', array(
				'type'	=>"season",
				'dataId'=> "",
				'limit' => 3,
				'view' 	=> "_sidebar",
			)); ?>

			<div class="portlet supportedby" id="yw2">
				<div class="portlet-content">
					<div class="top league">
						<h2>При поддержке</h2>
						<a href="http://fanclub-fakel.ru" target="_blank">
							<img src="/images/site/fanclub_fakel_logo.png">
							<h5>Официальный фан-клуб футбольного клуба «Факел»</h5>
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
