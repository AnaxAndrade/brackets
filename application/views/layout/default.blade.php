<!DOCTYPE html>
<html lang="en">  
<head>  
    <meta charset="utf-8">  
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="user-scalable" content="no">
    <link rel="apple-touch-icon" href="public/img/apple-touch-icon.png" />

    <title>BRACKET</title>  
    
	<link href="<?=URL::to_asset('assets/style/css/style.css')?>" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
    <?php if( ! isset($hideMenuBar)): ?>
    <header>
        <div class="wrapper">
        @yield('headerBtn')
        <h1>BRACKET</h1>
        </div>
    </header>
    <?php endif; ?>
    @yield('content')
</body>
</html>