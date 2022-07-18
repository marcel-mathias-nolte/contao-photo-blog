<?php

\Contao\ClassLoader::addNamespace('Psi');

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Listing'   	            => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Listing.php',
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Module'		            => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Module.php',
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Reader' 		            => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Reader.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\ArchiveMenu'              => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/ArchiveMenu.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\ArchiveMenuHelper'               => 'vendor/marcel-mathias-nolte/contao-photo-blog/ArchiveMenuHelper.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Categories'               => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Categories.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Orte'                     => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Orte.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Models'                   => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Models.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Fotografen'               => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Fotografen.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\CategoriesHelper'   	            => 'vendor/marcel-mathias-nolte/contao-photo-blog/CategoriesHelper.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\OrtHelper'                       => 'vendor/marcel-mathias-nolte/contao-photo-blog/OrtHelper.php',
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Helper'       		            => 'vendor/marcel-mathias-nolte/contao-photo-blog/Helper.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Module\\Tags'   	                => 'vendor/marcel-mathias-nolte/contao-photo-blog/Module/Tags.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\TagsHelper'   		            => 'vendor/marcel-mathias-nolte/contao-photo-blog/TagsHelper.php',
    'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\PushHelper'   		            => 'vendor/marcel-mathias-nolte/contao-photo-blog/PushHelper.php',
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Model\\ArticleModel'       		=> 'vendor/marcel-mathias-nolte/contao-photo-blog/Model/ArticleModel.php',
	'MarcelMathiasNolte\\ContaoPhotoBlogBundle\\Model\\ArchiveModel'       		=> 'vendor/marcel-mathias-nolte/contao-photo-blog/Model/ArchiveModel.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_news4ward_list'                            => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
	'mod_news4ward_reader'                          => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
	'news4ward_filter_hint'                         => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
	'news4ward_list_item'                           => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
	'news4ward_list_headline'                       => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_archivemenu' 				    => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_categories' 					    => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_orte'      					    => 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_models'      					=> 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_fotografen'      				=> 'vendor/marcel-mathias-nolte/contao-photo-blog/Resources/contao/templates',
    'mod_news4ward_tags' 					        => 'system/modules/news4ward_tags/templates',
));
