<?php

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['news4ward_archives']      = array('Blog archives', 'Please select one or more blog archives.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_featured']      = array('Featured entries', 'Here you can choose how featured entries are handled.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_perPage']      = array('Entries per page', 'The number of entries per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_skipFirst']      = array('Skip entries', 'Here you can define how many entries will be skipped.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_numberOfItems'] = array('Number of entries', 'Here you can limit the number of entries. Set to 0 to show all.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_jumpToCurrent'] = array('No period selected', 'Here you can define what to display if no period is selected.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_metaFields']    = array('Meta fields', 'Here you can select the meta fields.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_template']      = array('Entries list template', 'Here you can select the blog entries list template.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_readerTemplate'] = array('Entries reader template', 'Here you can select the blog entries reader template.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_format']        = array('Archive format', 'Here you can choose the news archive format.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_startDay']      = array('Week start day', 'Here you can choose the week start day.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_order']         = array('Sort order', 'Here you can choose the sort order.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_showQuantity']  = array('Show quantity', 'Show the number of entries relative to the specific filter.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_facebookMeta']  = array('Create meta-data for Facebook', 'Facebook meta-data are atributes, which are used/shared on Facebook. What more Teaser image will be used for Facebook.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_filterHint']  	= array('Current filter indicator', 'The indicator and currently used/selected filter is shown on page by Insert-Tag {{news4ward::filter_hint}}. The value of this field (e.g. <em>Category:</em>) describes the actual filter (e.g. <em>Sport</em>). On the page will be visible <em>Category: Sport</em>.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint']  	= array('Time constraint', 'Here you can select time interval to limit entries appearance.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_overwriteArchiveJumpTo']  	= array('Overwrite the archive jumpto page', 'Choose a separate reader page.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_ignoreFilters']  	= array('Ignore filter', 'This item-list ignores all filters.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_archivemenu_type']    = array('Archive format', 'Choose a method for archive grouping.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_archivemenu_type_reference'] = array('year'=>'year','month'=>'month');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_count']  		= array('Number of displayed tags', 'Here you can limit the maximal number of tags.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_minsize']  	= array('Minimum font size', 'Minimum font size in pixels.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_maxsize']  	= array('Maximum font size', 'Maximum font size in pixels.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_tresholds']  	= array('Graduation', 'Here you can enter graduation value.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_unit']  		= array('Graduation unit', 'Here you can select the graduation unit.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_shuffle']  	= array('Random sort', 'Sort the tags randomly.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_tags_random']  		= array('Random selection', 'The tags are selected randomly instead of relevance.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news4ward_day']     = 'Day';
$GLOBALS['TL_LANG']['tl_module']['news4ward_month']   = 'Month';
$GLOBALS['TL_LANG']['tl_module']['news4ward_year']    = 'Year';
$GLOBALS['TL_LANG']['tl_module']['hide_module']  = 'Hide module';
$GLOBALS['TL_LANG']['tl_module']['show_current'] = 'Jump to the current period';
$GLOBALS['TL_LANG']['tl_module']['all_items']    = 'Show all blog entries';
$GLOBALS['TL_LANG']['tl_module']['featured']     = 'Show featured blog entries only';
$GLOBALS['TL_LANG']['tl_module']['unfeatured']   = 'Skip featured blog entries';

$GLOBALS['tl_module']['news4ward_order_ref']	= array
(
	'start ASC'	=> 'start ASC',
	'start DESC'	=> 'start DESC',
	'title ASC'	=> 'title ASC',
	'title DESC'	=> 'title DESC',
	'random' => 'random'
);

$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['all'] = 'all entries';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['cur_month'] = 'current month';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['cur_year'] = 'current year';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_7'] = 'last week';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_14'] = 'last 2 weeks';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_30'] = 'last month';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_90'] = 'last 3 months';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_180'] = 'last 6 months';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_365'] = 'last year';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_two'] = 'last 2 years';
