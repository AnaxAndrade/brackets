<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}
	
	p { font-family: Helvetica; }
	p strong { color: #484848; }
	
	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 16px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 7px 10px 7px 10px;
		line-height: 1.2;
		tab-size: 1;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	
	.roundListElem {
		float: left;
		padding: 0 15px 0 0;
		width: 24%;
	}
		.roundListElem:last-child {
			padding: 0;
		}
		.roundListElem .emptyMatch {
			background-color: #ebf4ff;
			padding: 30px 55px;
		}
			.roundListElem .emptyMatch p {
				font-size: 18px;
				color: #ac0500;
				padding-left: 47px;
			}
		.roundListElem h2 {
			text-align: center;
		}
		.roundListElem ol {
			margin: 0 0 0 15px;
			padding: 0 0 0 10px;
		}
			.roundListElem ol li {
				padding: 7px 0;
				border-bottom: 2px solid #ececec;
				color: #ababab;
				font-size: 18px;
			}
				.roundListElem li p {
					margin: 0;
					padding: 0;
					color: #676767;
					font-size: 12px;
				}
				.roundListElem li strong {
					width: 90px;
				}
	.clear {
		overflow: auto;
	}
	.floatleft {
		float: left;
	}
	.floatright {
		float: right;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Bracket Output</h1>
	<div id="body">
		<p>This is the data provided after initiation</p>
		<code>
		<?php print '<pre>';		
		print_r($this->bracket->developer());
		print '</pre>';
		?></code>
		<hr />
		<div class="clear">
			<?php for($i=1;$i<=$this->bracket->rounds;$i++):?>
			<div class="roundListElem">
				<h2><?=$this->bracket->getRoundId($i)?></h2>
				<ol>
				<?php foreach($this->bracket->matches[$this->bracket->getRoundId($i)] as $k => $match):?>
					<?php if( ! isset($match['teams'])):  ?>
					<li class="emptyMatch" style="padding-top:<?=$i*22?>px;padding-bottom:<?=$i*22?>px">
						<p>Match <?=$k+1?></p>
					</li>
					<?php else: ?>
						<li>
							<p><strong>Home Team</strong> <?=($match['winner'] == 'away' ? '<s>' : '').implode(', ',$match['teams']['home']).($match['winner'] == 'away' ? '</s>' : '')?></p>
							<p><strong>Away Team</strong> <?=isset($match['teams']['away']) ? ($match['winner'] == 'home' ? '<s>' : '').implode(', ',$match['teams']['away']).($match['winner'] == 'home' ? '</s>' : '') : 'NOT SET'?></p>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
				</ol>
			</div>
			<?php endfor; ?>
		</div>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>