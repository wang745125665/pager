<?php
	echo '<style>
	#pager ul { list-style: none; }
	#pager li { display: inline-block; }
	#pager a { padding: 6px; text-decoration: none; color: blue;}
	#pager a.active { color: red; }
	#pager a:hover { color: #666; text-decoration: none; }
	#pager a.disable {}
	</style>';
	require_once('Pager.php');
	//模拟数据
	$total 	= 100;
	$p 		= $_GET['p'] ? : 1;
	$pager = new Pager($total, $p, array('limit'=>10));
	echo '<div id="pager">';
	echo $pager->show();
	echo '</div>';