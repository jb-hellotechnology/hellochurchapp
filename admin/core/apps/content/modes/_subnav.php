<?php


	PerchUI::set_subnav(array(
		array('page'=>array(
						'core/apps/content',
						'core/apps/content/page',
						'core/apps/content/page/add',
						'core/apps/content/page/edit',
						'core/apps/content/page/delete',
						'core/apps/content/page/details',
						'core/apps/content/page/url',
						'core/apps/content/edit',
						'core/apps/content/options',
						'core/apps/content/delete',
						'core/apps/content/denied',
						'core/apps/content/republish',
						'core/apps/content/reorder',
						'core/apps/content/reorder/region',
						'core/apps/content/revisions',
						'core/apps/content/revisions/revert',						
						), 
				'label'=>'Pages'),
		array('page'=>array(
						'core/apps/content/manage/collections',
						'core/apps/content/manage/collections/edit',
						'core/apps/content/manage/collections/delete',
						'core/apps/content/manage/collections/import',
						'core/apps/content/collections',
						'core/apps/content/collections/edit',
						'core/apps/content/collections/edit/options',
						'core/apps/content/collections/options',
						'core/apps/content/collections/import',
						'core/apps/content/delete/collection/item',	
						'core/apps/content/reorder/collection',
						), 
				'label'=>'Collections', 'priv'=>'content.collections.manage'),
		array('page'=>array(
						'core/apps/content/page/templates',
						'core/apps/content/page/templates/edit',
						'core/apps/content/page/templates/delete'), 
				'label'=>'Master pages', 'priv'=>'content.templates.configure'),
		array('page'=>array(
						'core/apps/content/navigation',
						'core/apps/content/navigation/edit',
						'core/apps/content/navigation/pages',
						'core/apps/content/navigation/reorder',
						'core/apps/content/navigation/delete'), 
				'label'=>'Navigation groups', 'priv'=>'content.navgroups.configure'),
		array('page'=>array(
						'core/apps/content/routes',
						'core/apps/content/routes/reorder',
						'core/apps/content/routes/delete'), 
				'label'=>'Routes',  'priv'=>'content.routes.manage'),

	));
