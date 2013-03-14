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

<hr class="spacer" />

<h3 class="center">Recently created brackets</h3>
<?php if($createdBrackets): ?>
<table>
	<thead>
		<th>Created</th>
		<th class="center">Players/Teams</th>
		<th class="center">Winner</th>
	</thead>
	<tbody>
		<?php foreach($createdBrackets as $b): ?>
			<tr>
				<td><?=date('M d', strtotime($b->created_at))?></td>
				<td class="center"><?=count($b->players)?>/<?=count($b->teams)?></td>
				<td class="center"><?=($b->winner ? $b->winner->playerNames() : '')?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>

<p>You have not created any brackets recently.  How about starting now?</p>
<?php endif; ?>

<hr class="spacer" />

<h3 class="center">Recently played brackets</h3>
<?php if($playedBrackets): ?>
<table>
	<thead>
		<th>Created</th>
		<th class="center">Players/Teams</th>
		<th class="center">Winner</th>
	</thead>
	<tbody>
		<?php foreach($playedBrackets as $b): ?>
			<tr>
				<td><?=date('M d', strtotime($b->created_at))?></td>
				<td class="center"><?=count($b->players)?>/<?=count($b->teams)?></td>
				<td class="center"><?=($b->winner ? $b->winner->playerNames() : '')?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>

<p>You have not created any brackets recently.  How about starting now?</p>
<?php endif; ?>

@endsection
