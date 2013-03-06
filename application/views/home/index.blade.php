@layout('layout/default')
@section('content')

<div id="stage">
	<div id="mainTitle">
		<h1>BRACKET</h1>
		<?php if($bracket): ?>
		<p>You already have a bracket running.  <?=HTML::link('bracket/teams', 'Go to your bracket!')?></p>
		<?php endif; ?>
	</div>
	<hr />
	<?=Form::open('bracket/create', 'POST', array('id'=>'createBracketForm'))?>
<!-- 		<fieldset>
			<?=Form::text('bracketName', false, array('placeholder'=>'Optional bracket name','class'=>'lg'))?>
		</fieldset>
 -->
<!--  		<fieldset>
			<?=Form::label('bracketType', 'Select a bracket type')?>
			<?=Form::select('bracketType', array('1' => 'Singles', '2' => 'Doubles'))?>
		</fieldset>
 -->
 		<fieldset>
			<?=Form::submit('Create A New Bracket', array('class'=>'btn lg span100'))?>
		</fieldset>
	<?=Form::close()?>
</div>

@endsection