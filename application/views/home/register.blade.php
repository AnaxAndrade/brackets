@layout('layout/default')
@section('content')

<h2 class="center">New Account</h2>
<hr />
<?php if($formErrors): ?>
	<div class="center">
	<?php foreach($formErrors->all() as $e): ?>
		<p class="txtAttn"><?=$e?></p>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?=Form::open('home/register', 'POST', array('id'=>'createUserForm', 'autocomplete'=>'off'))?>
	<fieldset>
		<?=Form::text('name', Input::old('name'), array('placeholder'=>'Your full name','class'=>'lg center', 'autocomplete'=>'off'))?>
	</fieldset>
	<fieldset>
		<?=Form::email('email', Input::old('email'), array('placeholder'=>'Your email address','class'=>'lg center', 'autocomplete'=>'off'))?>
	</fieldset>
	<fieldset>
		<?=Form::password('password', array('placeholder'=>'Top Secret Password','class'=>'lg center', 'autocomplete'=>'off'))?>
	</fieldset>

	<fieldset>
		<?=Form::submit('Create My Account', array('class'=>'btn lg span100'))?>
	</fieldset>
<?=Form::close()?>
<hr />
<p class="center"><?=HTML::link('home/login', 'I already have an account and want to log in.')?></p>
<hr />

@endsection
