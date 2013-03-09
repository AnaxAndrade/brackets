@layout('layout/default')

@section('headerBtn')
@endsection

@section('content')

<?php if($bracket->winner): ?>
	<?=HTML::link('bracket/reset', 'Start A New Bracket', array('class'=>'btn success'))?>
<?php endif; ?>

<div id="tournament">
	<div id="bracket">
		<?php foreach($bracket->rounds as $rk => $round): ?>
			<div class="round <?=($bracket->current_round== $round->id ? 'active' : '')?>" style="<?=( Holmes::is_mobile() ? ($rk === 0 ? 'display:block' : 'display:none') : '')?>">
				<h2 class="title"><?=$round->name?></h2>
				<hr />
				
				<?php foreach($round->matches as $k => $match):?>
					<div class="match <?=($match->completed_at ? 'complete' : '')?>">
					<span class="vs">vs</span>
					<?php if( ! $match->teams):  ?>
					
						<div class="emptyMatch">
							<p>Match <?=$k+1?></p>
						</div>
					
					<?php else: ?>
					
						<?php for($i=0;$i<2;$i++): ?>
							<div class="side <?=$match->completed_at ? '' : 'active'?> <?=$i==0?'home':'away'?> <?=(!isset($match->teams[$i])?'unassigned':'')?> <?=($match->winning_team_id == @$match->teams[$i]->id ? 'winner' : ($match->completed_at ? 'loser' : ''))?>">
								<h3><?=$i==0?'Home':'Away'?></h3>								
								<?php if(isset($match->teams[$i])): ?>
									<p><?=$match->teams[$i]->playerNames()?></p>

									<?=HTML::link('bracket/set_match_winner/'.$match->id.'/'.$match->teams[$i]->id, 'Winner', array('class'=>'btn sm winnerBtn','style="display:none"'))?>
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

@endsection
