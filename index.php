<!doctype html>
<html class="no-js" lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>PIW: PHP Image Works</title>

	<link rel="stylesheet" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Asap:400,700,700italic,400italic' rel='stylesheet' type='text/css'>
	<script src="js/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
	<header>
		<hgroup>
			<h1 id="logo"><small>Marcos Sandrini's</small>PIW</h1>
			<h2 id="sub">PHP Image Works</h1>
		</hgroup>
	</header>
	<div id="conteudo" role="main" class="clearfix">
		<hgroup>
			<h3 id="instr">An easy-to-deal script to resize and blend overlays on images, on-the-fly.</h3>
		</hgroup>
		<div id="xp">
			<p>&nbsp;</p><p><b>PIW</b> is a PHP script that can resize and apply Photoshop-like layer effects on-the-fly, to any images you want. <br>You can apply effects to user-sent images, products from e-shops, you name it...</p><p>See the usage examples below, it should be easy enough. You MUST, tough, have a PHP-capable server for it to run.</p>
			
			<div id="usage">
				To use the script, download the package and put the piw.php file in your website directory. After that, just link it just like an ordinary image:
				<code>&lt;img src=&quot;piw.php?image=[image file]&amp;w=[width]&amp;h=[height]&amp;colorlayer=[blending color]&amp;blending=[blending mode]&amp;opacity=[opacity]&quot; /&gt;</code>
				Example 1: Just resize
				<code>&lt;img src=&quot;piw.php?file=myphoto.jpg&amp;w=100&amp;h=80&quot; /&gt;</code>
				Example 2: Red overlay (normal blending, 50% opacity)
				<code>&lt;img src=&quot;piw.php?file=otherphoto.png&amp;colorlayer=F00&amp;opacity=50&quot; /&gt;</code>
				Example 3: Resize &amp; gray overlay (multiply blending, full opacity)
				<code>&lt;img src=&quot;piw.php?file=picture.jpg&amp;colorlayer=999&amp;blending=multiply&amp;w=200&amp;h=200&quot; /&gt;</code>				
			</div>
			
			<div id="tabela">
				<div class="linha clearfix">
					<div class="coluna">
						<b>Parameter</b>
					</div>
					<div class="coluna coluna2">
						<b>Example</b>
					</div>
					<div class="coluna coluna3">
						<b>Explanation</b>
					</div>
				</div>
				
				<div class="linha clearfix">
					<div class="coluna">
						<i>image</i>
					</div>
					<div class="coluna coluna2">
						image=photo.jpg
					</div>
					<div class="coluna coluna3">
						Image file to be processed, JPG, GIF or PNG. The only required parameter.
					</div>
				</div>
				
				<div class="linha clearfix">
					<div class="coluna">
						<i>w</i> and <i>h</i> (optional)
					</div>
					<div class="coluna coluna2">
						w=100&amp;h=80
					</div>
					<div class="coluna coluna3">
						Maximum width and maximum height. If these are not specified, resulting image will not be resized.
					</div>
				</div>
				
				<div class="linha clearfix">
					<div class="coluna">
						<i>colorlayer</i> (optional)
					</div>
					<div class="coluna coluna2">
						colorlayer=FC0
					</div>
					<div class="coluna coluna3">
						Color of the layer to be blended into the image. Color specification has to be hex (six or three characters) without the hashtag character(#). If not specified, no color layer blending happens.
					</div>
				</div>
				
				<div class="linha clearfix">
					<div class="coluna">
						<i>blendingmode</i> (optional)
					</div>
					<div class="coluna coluna2">
						blendingmode=multiply
					</div>
					<div class="coluna coluna3">
						Blending mode of the color layer (specified with <var>colorlayer</var>), defaults to "normal". Blending modes available:<br><br>
						<img src="bmodes.jpg" alt="Blending modes: multiply, screen, iflighter, ifdarker, softoverlay, hardlight, normal" style="display:block; margin-bottom:20px;">
					</div>
				</div>
				
				<div class="linha clearfix">
					<div class="coluna">
						<i>opacity</i> (optional)
					</div>
					<div class="coluna coluna2">
						opacity=50
					</div>
					<div class="coluna coluna3">
						Opacity of the color layer (specified with <var>colorlayer</var>), ranging from 1 to 100. Defaults to 100.
					</div>
				</div>
				
			</div>
		</div>
		
		<a href="piw-01.zip" id="download">Download PIW <small>(version 0.1)</small></a>	
		
	</div>
	
	
	
	<footer>
		<div class="version">Version 0.1: Initial release</div>
		<span class="copy">&copy; 2012 <a href="http://msandrini.com" target="_blank">Marcos Sandrini</a> - <a href="twitter.com/dasdesignbr">Contact me</a></span>
	</footer>

	<script src="js/libs/jquery-1.7.1.min.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
	
	</body>
</html>