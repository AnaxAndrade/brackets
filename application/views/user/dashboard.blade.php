@layout('layout/default')
@section('content')

<h2 class="center">Dashboard</h2>
<hr />
<?php if($bracket): ?>
	<h4 class="center">You are in an active bracket</h4>
	<ul>
		<li><strong>Round</strong> : <?=$bracket->current_round ? $bracket->currentRound->index.' - '.$bracket->currentRound->name : 'not started'?></li>
		<li><strong>Players</strong> : <?=count($bracket->players)?></li>
		<li><strong>Teams</strong> : <?=count($bracket->teams)?></li>
	</ul>

	<hr />
	 <?=HTML::link('bracket/tournament', 'Go to the bracket.', array('class'=>'btn sm'))?>

<?php else: ?>
	 <?=HTML::link('bracket', 'Start A New Tournament', array('class'=>'btn med'))?>

<?php endif; ?>
<hr />

@endsection
