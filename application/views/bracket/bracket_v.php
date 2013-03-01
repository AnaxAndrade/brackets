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
		<?php if($bracket->results->winner()): ?>
		<div id="winner">
			<h1>Winner : <?=implode(', ',$bracket->results->winner())?></h1>
		</div>
		<?php endif; ?>

		<?php for($i=1;$i<=$bracket->rounds;$i++):?>
			<div class="round">
				<h2 class="title"><?=$bracket->getRoundId($i)?></h2>
				<hr />
				<?php foreach($bracket->matches[$bracket->getRoundId($i)] as $k => $match):?>
					<div class="match">
					<?php if( ! isset($match['teams'])):  ?>
						<div class="emptyMatch">
							<p>Match <?=$k+1?></p>
						</div>
					<?php else: ?>
						<div class="side <?=($match['winner'] == 'home' ? 'winner' : ($match['winner'] == 'away' ? 'loser' : ''))?>">
							<h3>Home</h3>
							<p><?=(isset($match['teams']['home']) ? implode(', ',$match['teams']['home']) : 'NOT SET')?></p>
						</div>
						<div class="side <?=($match['winner'] == 'away' ? 'winner' : ($match['winner'] == 'home' ? 'loser' : ''))?>">
							<h3>Away</h3>
							<p><?=(isset($match['teams']['away']) ? implode(', ',$match['teams']['away']) : 'NOT SET')?></p>
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