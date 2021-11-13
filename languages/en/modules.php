<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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

// BE-Modules
$GLOBALS['TL_LANG']['MOD']['news4ward']['0']          = "Blog entries";
$GLOBALS['TL_LANG']['MOD']['news4ward']['1']          = "Blog entries administration";

// FE-Modules
$GLOBALS['TL_LANG']['FMD']['news4ward']               = 'Blog';
$GLOBALS['TL_LANG']['FMD']['news4wardList']           = array('Entries List', 'Adds a list of blog entries to the page.');
$GLOBALS['TL_LANG']['FMD']['news4wardReader']         = array('Entries Reader', 'Shows details of a blog entry.');
$GLOBALS['TL_LANG']['FMD']['news4wardArchiveMenu']    = array('Archive-Filter', 'Generates a navigation menu to browse the blog archive(s).');
$GLOBALS['TL_LANG']['FMD']['news4wardCategories']     = array('Categories-Filter', 'Adds a list/filter of used categories to the page.');
$GLOBALS['TL_LANG']['FMD']['news4wardTags']           = array('Tagcloud', 'Generates a list/filter of entries tags on the page.');

?>
