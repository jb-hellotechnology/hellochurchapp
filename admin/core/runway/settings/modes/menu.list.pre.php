<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

	$MenuItems = new PerchMenuItems;
	$top_level_menus = $MenuItems->get_top_level($Paging);


