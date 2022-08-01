<?php
/**
 * Created by PhpStorm.
 * User: Настя
 * Date: 26.05.2017
 * Time: 21:37
 */
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title"><span class="sudoku"></span>VIVA SUDOKU!</h1>
				<img src="/images/site/league_logo_sudoku.png">
				<div class="tours sudoku">
					<h1>Архивы</h1>

					<?php foreach($seasons as $ss):?>
						<p class="bg-info" style="padding:10px; background:#b7ecb7;">
							<a href="/sudoku/archive?s=<?php echo $ss->alias;?>"><?php echo $ss->name;?></a>
						</p>
					<?php endforeach;?>

				</div>
			</div>
		</div>

		<div class="col-lg-6 right">
			<div class="league_sidebar">
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"bombers",
					'season' => $season->alias,
				)); ?>
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"goleadors",
					'season' => $season->alias,
				));?>

			</div>
		</div>

	</div>
</div>