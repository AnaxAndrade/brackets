@layout('layout/default')

@section('content')

<h2 class="center">Bracket Players</h2>
<hr />
<div class="clear">
	<div id="playerList" class="clear">
		<ol>
		<?php if($bracket->players): ?>
			<?php foreach($bracket->players as $k => $p): ?>
				<li>
					<button class="listOpt listRemove confirm" data-confirm-msg="Are you sure you want to remove this player?">Remove</button>
					<h3><?=sprintf('%s %s', $p->first_name, $p->last_name)?></h3>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<p class="emptyListMsg">You have not added any players yet.</p>
		<?php endif; ?>
		</ol>
		<hr />
	</div>


	<?=Form::open('bracket/create_player', 'POST', array('id'=>'createBracketPlayerForm'))?>
		<fieldset class="floatleft span60">
			<?=Form::text('playerName', false, array('placeholder'=>'New Player Full Name','class'=>'med'))?>
		</fieldset>
		<fieldset class="floatleft">
			<?=Form::submit('Add Player', array('class'=>'btn'))?>
		</fieldset>
	<?=Form::close()?>
</div>
<hr />
@section('headerBtn')
	<?=HTML::link('bracket/pick_teams', 'Pick Teams', array('class' => 'btn lc center success'))?>
@endsection
@endsection