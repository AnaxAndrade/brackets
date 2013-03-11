@layout('layout/default')

@section('content')

<h2 class="center">Bracket Teams</h2>
<hr />
<div id="bracket">
	<?php if($teams = $bracket->teams): ?>
		<ul>
		<?php foreach($teams as $k => $t): ?>
			<li>
				<h1 class="center"><?=sprintf('%s', $t->playerNames())?></h1>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>There are no teams. <?=HTML::link('bracket/players', 'Add players and pick teams')?></p>
	<?php endif; ?>
</div>
<hr />
@section('headerBtn')
	<?=HTML::link('bracket/players', 'Players', array('class' => 'btn med floatleft'))?>
	<?php if($teams = $bracket->teams): ?>
		<?=HTML::link('bracket/generate_tournament', 'Build Bracket', array('class' => 'btn med success floatright'))?>
	<?php endif; ?>
@endsection

@endsection