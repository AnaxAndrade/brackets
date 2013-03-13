@layout('layout/default')
@section('content')

<div id="mainTitle">
	<h1>Bracket</h1>
	<hr />
	<h2 class="center">Sign into your<br /> Bracket account</h2>
	<hr />
</div>
<div class="mediaBreak mediaBreakLeft45">
	<?=Form::open('home/login', 'POST', array('id'=>'loginForm', 'autocomplete'=>'off'))?>
		<fieldset>
			<?=Form::email('email', Input::old('email'), array('placeholder'=>'Your email address','class'=>'lg center', 'autocomplete'=>'off'))?>
		</fieldset>
		<fieldset>
			<?=Form::password('password', array('placeholder'=>'Top Secret Password','class'=>'lg center', 'autocomplete'=>'off'))?>
		</fieldset>

		<fieldset>
			<?=Form::submit('Let Me In', array('class'=>'btn med success span100'))?>
		</fieldset>
	<?=Form::close()?>
</div>
<div class="mediaBreak mediaBreakRight45">

	<hr />
	<?=HTML::link('home/register', 'Create an Account', array('class'=>'btn span100 center'))?>
	<hr />
</div>
@endsection