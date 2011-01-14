<!DOCTYPE html>
<html>
<head>
	<title>Schedulr - <?php echo $title_for_layout ?></title>
	<?php echo $this->Html->script(array('jquery.min', 'facebox', 'json2')); ?>
	<?php echo $this->Html->css(array('facebox', 'faceplant', 'style')); ?>
</head>
<body>
	<?php echo $content_for_layout ?>
	<div id = "footer">
		<p>Copyright &copy; 2010 - Schedulr</p>
		<p>Developed by Conor Branagan | Code hosted on <a href = "https://github.com/conorbranagan/Schedulr">GitHub</a></p>
	</div>
</body>
</html>