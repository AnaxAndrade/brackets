@layout('layout/default')

@section('headerBtn')
	<?=HTML::link('bracket/add_players', '&laquo; Add Players', array('class' => 'btn sm floatleft'))?>
	<?=HTML::link('bracket/generate', 'Build Bracket &raquo;', array('class' => 'btn sm success floatright'))?>
@endsection

@section('content')

<div id="stage">
	<h2>Bracket Teams</h2>
	<hr />
	<div id="bracket">
		<?php if($teams = $bracket->teams): ?>
			<ul class="playerList">
			<?php foreach($teams as $k => $t): ?>
				<li>
					<h3><?=sprintf('%s. %s', $k+1, $t->playerNames())?></h3>
					<div class="options">
						<a href="" class="listRemove">Remove</a>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p>There are no teams. <?=HTML::link('bracket/add_players', 'Add players and pick teams')?></p>
		<?php endif; ?>
	</div>
</div>

@endsection