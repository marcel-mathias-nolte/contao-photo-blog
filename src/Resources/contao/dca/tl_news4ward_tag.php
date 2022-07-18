<?php

$GLOBALS['TL_DCA']['tl_news4ward_tag'] = array
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
		'tag' => array
		(
            'sql'                     => "varchar(255) NOT NULL default ''"
		)
    )
);
