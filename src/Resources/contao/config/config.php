<?php

// BE-Module
use MarcelMathiasNolte\ContaoPhotoBlogBundle\TagsWidgetResponder;

$GLOBALS['BE_MOD']['content']['news4ward'] = array(
	'tables'                                    => array('tl_news4ward','tl_news4ward_article','tl_content'),
	'icon'                                      => 'bundles/contaophotoblog/icon.png',
	'javascript'                                => 'bundles/contaophotoblog/News4ward.js',
	'stylesheet'                                => 'bundles/contaophotoblog/News4ward.css',
);

// FE-Modules
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'news4ward' => array
	(
		'news4wardList'                         => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Listing',
		'news4wardReader'                       => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Reader',
        'news4wardArchiveMenu'                  => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\ArchiveMenu',
        'news4wardCategories'                   => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Categories',
        'news4wardOrte'                         => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Orte',
        'news4wardModels'                       => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Models',
        'news4wardFotografen'                   => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Fotografen',
        'news4wardTags'                         => 'MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Tags'
	)
));


// add news archive permissions
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward';
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward_newp';
$GLOBALS['TL_PERMISSIONS'][]                    = 'news4ward_itemRights';

// Register auto_item
//$GLOBALS['TL_AUTO_ITEM'][] = 'items';

// Register hook to add items to the indexer
//$GLOBALS['TL_HOOKS']['getSearchablePages'][]  = array('MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'getSearchablePages');

// Cronjob for feed generation
$GLOBALS['TL_CRON']['daily'][]                  = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'generateFeeds');

// hook for custom inserttags
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'inserttagReplacer');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\ArchiveMenuHelper','archiveFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\CategoriesHelper','categoryFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\OrtHelper','ortFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\OrtHelper','stadtFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\OrtHelper','fotografFilter');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\OrtHelper','modelFilter');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\CategoriesHelper','categoryParseArticle');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\OrtHelper','ortParseArticle');
$GLOBALS['TL_HOOKS']['News4wardListFilter'][]   = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\TagsHelper','listFilter');
$GLOBALS['TL_HOOKS']['News4wardParseArticle'][] = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\TagsHelper','tagsParseArticle');

if (TL_MODE == 'BE')
{
	// hook for ajax requests
	$GLOBALS['TL_HOOKS']['executePreActions'][] = array('\MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'ajaxHandler');
}

// Models
$GLOBALS['TL_MODELS']['tl_news4ward_article']   = '\MarcelMathiasNolte\ContaoPhotoBlogBundle\Model\ArticleModel';
$GLOBALS['TL_MODELS']['tl_news4ward']           = '\MarcelMathiasNolte\ContaoPhotoBlogBundle\Model\ArchiveModel';

$GLOBALS['BE_FFL']['tags'] = '\MarcelMathiasNolte\ContaoPhotoBlogBundle\Widgets\TagsWidget';


array_insert($GLOBALS['TL_CTE'], 2, array(
    'text' => array(
        'news4ward_content' => '\MarcelMathiasNolte\ContaoPhotoBlogBundle\Elements\ContentNews4WardContent'
    )
));

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'tags-widget-ajax') {

    $x = new TagsWidgetResponder();
    die();
}