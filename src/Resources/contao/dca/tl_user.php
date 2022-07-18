<?php

/**
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('fop;', 'fop;{news4ward_legend},news4ward,news4ward_newp,news4ward_itemRights;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('fop;', 'fop;{news4ward_legend},news4ward,news4ward_newp,news4ward_itemRights;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);


/**
 * Add fields to tl_user
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['news4ward'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['news4ward'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_news4ward.title',
	'eval'                    => array('multiple'=>true),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['news4ward_newp'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['news4ward_newp'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['news4ward_itemRights'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['news4ward_itemRights'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('onlyOwn'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_user']['news4ward_itemRights_reference'],
	'eval'                    => array('multiple'=>true),
    'sql'                     => "blob NULL"
);

