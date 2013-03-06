@layout('layout/default')

@section('headerBtn')
	<?=HTML::link('bracket/teams', '&laquo; View Players', array('class' => 'btn sm floatleft'))?>
@endsection

@section('content')
<div id="bracket">
	<?php if($bracket->winner): ?>
		<div id="winner">
			<h1>Winner : <?=$bracket->winner->playerNames()?></h1>
		</div>
		<hr />
	<?php endif; ?>

	<?php foreach($bracket->rounds as $round):?>

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
								<p><?=$match->teams[$i]->playerNames()?></p>
								<?php if( ! $match->winning_team_id): ?>
									<?=HTML::link('bracket/set_match_winner/'.$match->id.'/'.$match->teams[$i]->id, 'Winner', array('class'=>'btn sm'))?>
								<?php endif; ?>
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
<?php if($bracket->winner): ?>
	<?=HTML::link('/', 'Start A New Bracket', array('class'=>'btn success'))?>
<?php endif; ?>

@endsection
