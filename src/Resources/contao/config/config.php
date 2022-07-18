<?php

/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward
 * @filesource
 * @licence LGPL
 */

// BE-Module
$GLOBALS['BE_MOD']['content']['news4ward'] = array(
	'tables'                                    => array('tl_news4ward','tl_news4ward_article','tl_content'),
	'icon'                                      => 'system/modules/news4ward/assets/icon.png',
	'javascript'                                => 'system/modules/news4ward/assets/News4ward.js',
	'stylesheet'                                => 'system/modules/news4ward/assets/News4ward.css',
);

// FE-Modules
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'news4ward' => array
	(
		'news4wardList'                         => '\News4ward\Module\Listing',
		'news4wardReader'                       => '\News4ward\Module\Reader',
        'news4wardArchiveMenu'                  => '\News4ward\Module\ArchiveMenu',
        'news4wardCategories'                   => '\News4ward\Module\Categories',
        'news4wardOrte'                         => '\News4ward\Module\Orte',
        'news4wardModels'                       => '\News4ward\Module\Models',
        'news4wardFotografen'                   => '\News4ward\Module\Fotografen',
        'news4wardTags'                         => '\News4ward\Module\Tags'
	)
));


// add news archive permissions
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward';
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward_newp';
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward_itemRights';

// Register auto_item
//$GLOBALS['TL_AUTO_ITEM'][] = 'items';

// Register hook to add items to the indexer
//$GLOBALS['TL_HOOKS']['getSearchablePages'][]  = array('\News4ward\Helper', 'getSearchablePages');

// Cronjob for feed generation
$GLOBALS['TL_CRON']['daily'][]                  = array('\News4ward\Helper', 'generateFeeds');

// hook for custom inserttags
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('\News4ward\Helper', 'inserttagReplacer');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\ArchiveMenuHelper','archiveFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\CategoriesHelper','categoryFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\OrtHelper','ortFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\OrtHelper','stadtFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\OrtHelper','fotografFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\OrtHelper','modelFilter');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\News4ward\CategoriesHelper','categoryParseArticle');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\News4ward\OrtHelper','ortParseArticle');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\News4ward\TagsHelper','listFilter');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\News4ward\TagsHelper','tagsParseArticle');

if (TL_MODE == 'BE')
{
	// hook for ajax requests
	$GLOBALS['TL_HOOKS']['executePreActions'][] = array('\News4ward\Helper', 'ajaxHandler');
}

// Models
$GLOBALS['TL_MODELS']['tl_news4ward_article']   = '\News4ward\Model\ArticleModel';
$GLOBALS['TL_MODELS']['tl_news4ward']           = '\News4ward\Model\ArchiveModel';
