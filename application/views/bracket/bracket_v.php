<!DOCTYPE html>
<html lang="en">  
<head>  
    <meta charset="utf-8">  
    <title>BRACKETS</title>  
	<link href="<?=URL::to_asset('assets/style/css/style.css')?>" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="stage">
	<div id="bracket">
		<?php if($tournament->results->winner()): ?>
		<div id="winner">
			<h1>Winner : <?=implode(', ',$tournament->results->winner())?></h1>
		</div>
		<?php endif; ?>

		<?php for($i=1;$i<=$tournament->rounds();$i++):?>
			<div class="round">
				<h2 class="title"><?=$tournament->getRoundId($i)?></h2>
				<hr />
				<?php foreach($tournament->matches()[$tournament->getRoundId($i)] as $k => $match):?>
					<div class="match <?=($match['winner'] !== false ? 'complete' : '')?>">
					<?php if( ! isset($match['teams'])):  ?>
						<div class="emptyMatch">
							<p>Match <?=$k+1?></p>
						</div>
					<?php else: ?>
						<div class="side home <?=(!isset($match['teams'][0])?'unassigned':'')?> <?=($match['winner'] === 0 ? 'winner' : ($match['winner'] === 1 ? 'loser' : ''))?>">
							<h3>Home</h3>
							<?php if(isset($match['teams'][0])): ?>
							<p>
								<?php foreach($match['teams'][0] as $index => $player): ?>
									<?=$player->first_name.($player->last_name?' '.$player->last_name:'').($index+1 < count($match['teams'][0]) ? ', ': '')?>
								<?php endforeach; ?>
							</p>
							<?php else: ?>
								<p>NOT SET</p>
							<?php endif; ?>
						</div>
						<div class="side away <?=(!isset($match['teams'][1])?'unassigned':'')?> <?=($match['winner'] === 1 ? 'winner' : ($match['winner'] === 0 ? 'loser' : ''))?>">
							<h3>Away</h3>
							<?php if(isset($match['teams'][1])): ?>
							<p>
								<?php foreach($match['teams'][1] as $index => $player): ?>
									<?=$player->first_name.($player->last_name?' '.$player->last_name:'').($index+1 < count($match['teams'][1]) ? ', ': '')?>
								<?php endforeach; ?>
							</p>
							<?php else: ?>
								<p>NOT SET</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>

</body>
</html>