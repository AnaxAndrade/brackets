@layout('layout/default')
@section('content')

<h2 class="center">Start A Bracket</h2>
<hr />

	<?php if($bracket): ?>
	<div class="alert notice">
		<button class="close">Close</button>
		<h4>Guess What</h4>
		<p>You already have a bracket running.  <?=HTML::link('bracket/tournament', 'Go to your bracket!')?></p>
	</div>
	<?php endif; ?>
<?=Form::open('bracket/create', 'POST', array('id'=>'createBracketForm'))?>
	<fieldset>
		<?=Form::text('bracketName', false, array('placeholder'=>'Optional bracket name','class'=>'lg center'))?>
	</fieldset>
 	<fieldset>
		<?=Form::select('bracketType', array('1' => 'Singles', '2' => 'Doubles'))?>
	</fieldset>

	<fieldset>
		<?=Form::submit('Start Your Bracket', array('class'=>'btn lg span100'))?>
	</fieldset>
<?=Form::close()?>

@endsection