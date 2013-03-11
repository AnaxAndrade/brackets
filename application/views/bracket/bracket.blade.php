@layout('layout/default')

@section('content')

<h3 class="center"><?=($bracket->name ? $bracket->name : 'Your Tournament')?></h3>
<hr />
<?php if($bracket->winner): ?>
	<h1 class="center">Winner : <span class="txtSuccess"><?=$bracket->winner->playerNames(' and ')?></span></h1>
	<hr />
	<?=HTML::link('bracket/reset', 'Start A New Bracket', array('class'=>'btn success'))?>
<?php endif; ?>
<div id="tournament">
	<div id="bracket">
		<?php foreach($bracket->rounds as $rk => $round): ?>
			<div class="round <?=($bracket->current_round== $round->id ? 'active' : '')?>" style="<?=( Holmes::is_mobile() ? ($rk === 0 ? 'display:block' : 'display:none') : '')?>">
				<h2 class="title"><?=$round->name?></h2>
				<hr />
				
				<?php foreach($round->matches as $k => $match):?>
					<div class="match <?=($match->completed_at ? 'complete' : (count($match->teams) == 2 ? 'ready' : ''))?>">
					
						<?php if( ! $match->teams):  ?>
							<div class="emptyMatch"><p>Match <?=$k+1?></p></div>
						<?php else: ?>
							<div class="flipElem front">
								<?php for($i=0;$i<2;$i++): ?>
									<div class="side <?=$match->completed_at?'':'active'?> <?=$i==0?'home':'away'?> <?=(!isset($match->teams[$i])?'unassigned':'')?> <?=($match->winning_team_id && $match->winning_team_id == @$match->teams[$i]->id ? 'winner' : ($match->completed_at ? 'loser' : ''))?>">
		 								<?php if(isset($match->teams[$i])): ?>
											<p><?=$match->teams[$i]->playerNames()?></p>

											<?=HTML::link('bracket/set_match_winner/'.$match->id.'/'.$match->teams[$i]->id, 'Winner', array('class'=>'btn sm winnerBtn','style="display:none"'))?>
										<?php else: ?>
											<p>NOT SET</p>
										<?php endif; ?>
									</div>						
								<?php endfor; ?>
							</div>
							<?php if(count($match->teams) > 1): ?>
							<div class="flipElem back">
								<button class="close">close</button>
								<?=Form::open('bracket/set_match_winner/'.$match->id, 'POST', array('id'=>'saveMatchResultsForm'))?>
									<div class="floatleft span27">
										<label><?=$match->teams[0]->playerNames(' and ')?></label>
										<?=Form::select('teamScoreHome', range(0,8), 0)?>
									</div>
									<div class="floatright span47">
										<label><?=$match->teams[1]->playerNames(' and ')?></label>
										<?=Form::select('teamScoreAway', range(0,8), 0)?>
									</div>
									<?=Form::submit('Save Match Results', array('class'=>'btn sm center span100'))?>
								<?=Form::close()?>
							</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				
				<?php endforeach; ?>
			
			</div>
		
		<?php endforeach; ?>
	</div>
</div>
@section('headerBtn')
@endsection

@endsection
