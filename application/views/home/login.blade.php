@layout('layout/default')
@section('content')

<div id="mainTitle">
	<h1>Login</h1>
</div>
<?=Form::open('home/login', 'POST', array('id'=>'loginForm', 'autocomplete'=>'off'))?>
	<fieldset>
		<?=Form::email('email', Input::old('email'), array('placeholder'=>'Your email address','class'=>'lg center', 'autocomplete'=>'off'))?>
	</fieldset>
	<fieldset>
		<?=Form::password('password', array('placeholder'=>'Top Secret Password','class'=>'lg center', 'autocomplete'=>'off'))?>
	</fieldset>

	<fieldset>
		<?=Form::submit('Let Me In', array('class'=>'btn lg success span100'))?>
	</fieldset>
<?=Form::close()?>

<hr />
<?=HTML::link('home/register', 'Create an Account', array('class'=>'btn span100 center'))?>
<hr />
@endsection