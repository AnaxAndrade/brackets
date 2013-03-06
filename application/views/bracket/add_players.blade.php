@layout('layout/default')

@section('headerBtn')
	<?=HTML::link('bracket/pick_teams', 'Pick Teams &raquo;', array('class' => 'btn sm success floatright'))?>
@endsection

@section('content')

<div id="stage">
	<h2>Bracket Players</h2>
	<hr />
	<div id="bracket">
		
		<?php if($bracket->players): ?>
			<ol class="playerList">
			<?php foreach($bracket->players as $k => $p): ?>
				<li>
					<h3><?=sprintf('%s. %s %s', $k+1, $p->first_name, $p->last_name)?></h3>
					<div class="options">
						<a href="" class="listRemove">Remove</a>
					</div>
				</li>
			<?php endforeach; ?>
			</ol>
		<?php else: ?>
			<p>You have not added any players yet.</p>
		<?php endif; ?>
		
		<hr />
		
		<?=Form::open('bracket/create_player', 'POST', array('id'=>'createBracketPlayerForm'))?>
			<fieldset class="floatleft span65">
				<?=Form::text('playerName', false, array('placeholder'=>'New Player Full Name','class'=>'med'))?>
			</fieldset>
			<fieldset class="floatleft">
				<?=Form::submit('Add Player', array('class'=>'btn'))?>
			</fieldset>
		<?=Form::close()?>
	</div>
</div>

@endsection