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

$GLOBALS['TL_DCA']['tl_news4ward_location_cache'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )
	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
		'name' => array
		(
            'sql'                     => "varchar(1024) NOT NULL default ''"
		),
		'label' => array
		(
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lat' => array
		(
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lon' => array
		(
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'quantity' => array
		(
            'sql'                     => "int(10) unsigned NOT NULL default 0"
		)
    )
);
