<?php

\Contao\ClassLoader::addNamespace('Psi');

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Psi\News4ward\Module\Listing'   	            => 'system/modules/news4ward/Module/Listing.php',
	'Psi\News4ward\Module\Module'		            => 'system/modules/news4ward/Module/Module.php',
	'Psi\News4ward\Module\Reader' 		            => 'system/modules/news4ward/Module/Reader.php',
    'Psi\News4ward\Module\ArchiveMenu'              => 'system/modules/news4ward/Module/ArchiveMenu.php',
    'Psi\News4ward\ArchiveMenuHelper'               => 'system/modules/news4ward/ArchiveMenuHelper.php',
    'Psi\News4ward\Module\Categories'               => 'system/modules/news4ward/Module/Categories.php',
    'Psi\News4ward\CategoriesHelper'   	            => 'system/modules/news4ward/CategoriesHelper.php',
	'Psi\News4ward\Helper'       		            => 'system/modules/news4ward/Helper.php',
    'Psi\News4ward\Module\Tags'   	                => 'system/modules/news4ward/Module/Tags.php',
    'Psi\News4ward\TagsHelper'   		            => 'system/modules/news4ward/TagsHelper.php',
	'Psi\News4ward\Model\ArticleModel'       		=> 'system/modules/news4ward/Model/ArticleModel.php',
	'Psi\News4ward\Model\ArchiveModel'       		=> 'system/modules/news4ward/Model/ArchiveModel.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_news4ward_list'                            => 'system/modules/news4ward/templates',
	'mod_news4ward_reader'                          => 'system/modules/news4ward/templates',
	'news4ward_filter_hint'                         => 'system/modules/news4ward/templates',
	'news4ward_list_item'                           => 'system/modules/news4ward/templates',
	'news4ward_list_headline'                       => 'system/modules/news4ward/templates',
    'mod_news4ward_archivemenu' 				    => 'system/modules/news4ward/templates',
    'mod_news4ward_categories' 					    => 'system/modules/news4ward/templates',
    'mod_news4ward_tags' 					        => 'system/modules/news4ward_tags/templates',
));
