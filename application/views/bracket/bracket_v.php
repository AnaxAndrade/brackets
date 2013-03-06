<!DOCTYPE html>
<html lang="en">  
<head>  
    <meta charset="utf-8">  
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width">
    <meta name="user-scalable" content="no">

    <title>BRACKETS</title>  
	<link href="<?=URL::to_asset('assets/style/css/style.css')?>" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="stage">
	<div id="bracket">
		<?php if($winner = $tournament->bracket->winner): ?>
		<div id="winner">
			<h1>Winner : <?=$winner->playerNames()?></h1>
		</div>
		<?php endif; ?>

		<?php foreach($tournament->bracket->rounds as $round):?>

			<div class="round">
				<h2 class="title"><?=$round->name?></h2>
				<hr />
				
				<?php foreach($round->matches as $k => $match):?>
					<div class="match <?=($match->completed_at ? 'complete' : '')?>">
					
					<?php if( ! $match->teams):  ?>
					
						<div class="emptyMatch">
							<p>Match <?=$k+1?></p>
						</div>
					
					<?php else: ?>
					
						<?php for($i=0;$i<2;$i++): ?>
							<div class="side <?=$i==0?'home':'away'?> <?=(!isset($match->teams[$i])?'unassigned':'')?> <?=($match->winning_team_id == @$match->teams[$i]->id ? 'winner' : '')?>">
								<h3><?=$i==0?'Home':'Away'?></h3>								
								<?php if(isset($match->teams[$i])): ?>
									<p>
										<?php foreach($match->teams[$i]->players as $index => $player): ?>
											<?=$player->first_name.($player->last_name?' '.$player->last_name:'').($index+1 < count($match->teams[$i]->players) ? ', ': '')?>
										<?php endforeach; ?>
									</p>
								<?php else: ?>
									<p>NOT SET</p>
								<?php endif; ?>
							</div>						
						<?php endfor; ?>
					
					<?php endif; ?>
					
					</div>
				
				<?php endforeach; ?>
			
			</div>
		
		<?php endforeach; ?>
	
	</div>
</div>

</body>
</html>