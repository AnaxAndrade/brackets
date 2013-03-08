@layout('layout/default')
@section('content')

<div id="mainTitle">
	<h1>BRACKET</h1>
</div>
	<?php if($bracket): ?>
	<div class="alert notice">
		<button class="close">Close</button>
		<h4>Guess What</h4>
		<p>You already have a bracket running.  <?=HTML::link('bracket/teams', 'Go to your bracket!')?></p>
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
		<?=Form::submit('Create A New Bracket', array('class'=>'btn lg span100'))?>
	</fieldset>
<?=Form::close()?>

@endsection