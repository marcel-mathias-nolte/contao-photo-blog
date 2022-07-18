<?php

$GLOBALS['TL_DCA']['tl_news4ward_article'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_news4ward',
		'ctable'                      => array('tl_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_news4ward_article', 'checkPermission'),
			array('tl_news4ward_article', 'generateFeed'),
			array('MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'setFiletreePath'),
		),
		'onsubmit_callback' 		  => array(
		    array('tl_news4ward_article', 'scheduleUpdate'),
            array('tl_news4ward_article', 'saveLocations')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index',
                'alias' => 'index'
            )
        )
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('start DESC'),
			'panelLayout'             => 'filter,limit;search,sort',
			'headerFields'            => array('title','protected'),
			'child_record_callback'   => array('tl_news4ward_article', 'listItem')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
            'toggle' => array
            (
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,\'%s\')"',
                'button_callback'     => array('tl_news4ward_article', 'toggleIcon')
            ),
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['edit'],
				'href'                => 'table=tl_content',
				'icon'                => 'edit.gif',
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_news4ward_article', 'editHeader'),
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				  => array('useFacebookImage', 'protected', 'showGallery'),
		'default'                     => '{title_legend},title,alias,category,camera,software,fotografen,comodels,ort,author,highlight,sticky;{layout_legend},pageTitle,description,keywords;{teaser_legend:hide},subheadline,teaser,showGallery,teaserImage,teaserImageCaption,teaserCssID;{facebook_legend},useFacebookImage;{tags_legend},tags;{expert_legend:hide},social,cssID;{protected_legend:hide},protected,guests;{publish_legend},start,stop,status'
	),

	'subpalettes' => array
	(
        'protected'                   => 'protect,groups,placeholder',
		'useFacebookImage'			  => 'facebookImage',
		'showGallery'			      => 'multiSRC,orderSRC,limit,lightbox'
	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'foreignKey'              => 'tl_news4ward.title',
            'sql'                     => "int(10) unsigned NOT NULL default 0",
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'published' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['title'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['alias'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_news4ward_article', 'generateAlias')
			),
            'sql'                     => "varbinary(128) NOT NULL default ''"

		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['author'],
			'inputType'               => 'select',
			'default'                 => \BackendUser::getInstance()->id,
			'exclude'                 => true,
			'foreignKey'              => 'tl_user.name',
			'filter'                  => 'true',
			'eval'                    => array('doNotCopy'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pageTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['pageTitle'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['keywords'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px;'),
            'sql'                     => "text NULL"
		),
        'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['description'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px;'),
            'sql'                     => "text NULL"
		),
		'highlight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'],
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'subheadline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['subheadline'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'teaserCssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserCssID'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
		),
		'teaserImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImage'],
			'inputType'               => 'fileTree',
			'exclude'                 => true,
			'eval'                    => array('fieldType'=>'radio', 'files'=>'true', 'filesOnly'=>true, 'extensions'=>'jpg,gif,png,jpeg'),
			'sql'                     => "binary(16) NULL"
		),
		'placeholder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['placeholder'],
			'inputType'               => 'fileTree',
			'exclude'                 => true,
			'eval'                    => array('fieldType'=>'radio', 'files'=>'true', 'filesOnly'=>true, 'extensions'=>'jpg,gif,png,jpeg'),
			'sql'                     => "binary(16) NULL"
		),
		'teaserImageCaption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImageCaption'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'social' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['social'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'default'				  => serialize(array('facebook', 'twitter','google','email')),
			'options'                 => array('facebook', 'twitter','google','email'),
			'eval'                    => array('multiple'=>true,'tl_class'=>''),
			'reference'               => &$GLOBALS['TL_LANG']['tl_news4ward_article'],
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['status'],
			'inputType'               => 'select',
			'exclude'                 => true,
			'filter'                  => true,
			'options'                 => array('published','review','draft'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'],
			'eval'                    => array('doNotCopy'=>true,'tl_class'=>'w50'),
            'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['start'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'default'				  => time(),
			'sorting'				  => true,
			'flag'					  => 8,
			'eval'                    => array('mandatory'=>true,'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'sticky' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
		),
		'useFacebookImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['useFacebookImage'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('submitOnChange'=>'true'),
            'sql'                     => "char(1) NOT NULL default '"
		),
		'facebookImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['facebookImage'],
			'inputType'               => 'fileTree',
			'exclude'                 => true,
			'eval'                    => array('fieldType'=>'radio', 'files'=>'true', 'filesOnly'=>true, 'extensions'=>'jpg,gif,png'),
            'sql'                     => "binary(16) NULL"
		),
        'protected' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['protected'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'protect' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['protect'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'groups' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['groups'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member_group.name',
            'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'clr'),
            'sql'                     => "blob NULL"
        ),
        'guests' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['guests'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'showGallery' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['showGallery'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'multiSRC' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['multiSRC'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['fieldType'=>'checkbox', 'files'=>true, 'multiple'=>true, 'tl_class'=>'clr', 'extensions'=>\Contao\Config::get('uploadTypes'), 'orderField'=>'orderSRC', 'isGallery'=>true],
            'sql'                     => 'blob NULL'
        ),
        'orderSRC' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['orderSRC'],
            'sql'                     => 'blob NULL'
        ),
        'limit' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['limit'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'numeric', 'tl_class'=>'w50'),
            'sql'                     => "int(10) NOT NULL default 0"
        ),
        'lightbox' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['lightbox'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'category' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['category'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'options_callback'        => array('tl_news4ward_article','getCategories'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'camera' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['camera'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'options_callback'        => array('tl_news4ward_article','getCameras'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50 clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'software' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['software'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'options_callback'        => array('tl_news4ward_article','getSoftwares'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'fotografen' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward']['fotografen'],
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'fotograf' => array
                    (
                        'label'     => array('Fotograf'),
                        'inputType'               => 'select',
                        'exclude'                 => true,
                        'options_callback'        => array('tl_news4ward_article','getFotografen'),
                        'eval'                    => array('includeBlankOption'=>true)
                    )
                ),
                'tl_class' => 'clr'
            ),
            'sql'                     => "blob NULL"
        ),
        'comodels' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward']['comodels'],
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'model' => array
                    (
                        'label'     => array('Model'),
                        'inputType'               => 'select',
                        'exclude'                 => true,
                        'options_callback'        => array('tl_news4ward_article','getCoModels'),
                        'eval'                    => array('includeBlankOption'=>true)
                    )
                )
            ),
            'sql'                     => "blob NULL"
        ),
        'ort' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['ort'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'options_callback'        => array('tl_news4ward_article','getOrte'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'tags' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['tags'],
            'exclude'                 => true,
            'inputType'				  => 'tags',
            'save_callback'			  => array(array('tl_news4ward_article','saveTags')),
            'load_callback'		  	  => array(array('tl_news4ward_article','loadTags')),
            'options_callback'		  => array('tl_news4ward_article','getAllTags'),
            'eval'					  => array('doNotSaveEmpty' => true)
        )
	)
);


class tl_news4ward_article extends Backend
{

	protected static $authorCache = array();

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('Database');
	}


    public function getAllTags()
    {
        $objTag = $this->Database->prepare('SELECT DISTINCT(tag) FROM tl_news4ward_tag ORDER BY tag')->execute();
        return $objTag->fetchEach('tag');
    }


    /**
     * Load Tags from the tags-table
     *
     * @param string $varValue
     * @param DataContainer $dc
     * @return array
     */
    public function loadTags($varValue,DataContainer $dc)
    {
        $objTag = $this->Database->prepare('SELECT tag FROM tl_news4ward_tag WHERE pid=? ORDER BY tag')->execute($dc->id);
        return $objTag->fetchEach('tag');
    }


    /**
     * Save Tags in the tags-table
     * @param string $varValue
     * @param DataContainer $dc
     * @return string empty
     */
    public function saveTags($varValue, DataContainer $dc)
    {
        $objTag = $this->Database->prepare('SELECT id,tag FROM tl_news4ward_tag WHERE pid=? ORDER BY tag')->execute($dc->id);
        $arrOldTags = array();
        while($objTag->next())	$arrOldTags[$objTag->id] = $objTag->tag;

        $varValue = deserialize($varValue,true);

        // calc difference
        $newTags = array_diff($varValue,$arrOldTags);
        $removedTags = array_diff($arrOldTags,$varValue);

        // insert new tags
        foreach($newTags as $tag)
        {
            $this->Database->prepare('INSERT INTO tl_news4ward_tag SET tstamp=?, pid=?, tag=?')->execute(time(),$dc->id,$tag);
        }

        // remove old tags
        if(count($removedTags))
        {
            $this->Database->execute('DELETE FROM tl_news4ward_tag WHERE pid='.$dc->id.' AND id IN ('.implode(',',array_keys($removedTags)).') ');
        }

        return '';
    }


    /**
     * Save Tags in the tags-table
     * @param string $varValue
     * @param DataContainer $dc
     * @return string empty
     */
    public function saveLocations($dc)
    {
        $objLister = $this->Database->execute("SELECT id, orte, jumpToList FROM tl_news4ward");
        $arrOrte = [];
        $arrJumpTo = [];
        while ($objLister->next()) {
            $arrJumpTo[$objLister->id] = PageModel::findByPk($objLister->jumpToList);
            $orte = deserialize($objLister->orte, true);
            if (count($orte) > 0) foreach ($orte as $ort) {
                if ($ort['lat'] && $ort['lon']) {
                    $arrOrte[$objLister->id . ', ' . $ort['name'] . ', ' . $ort['stadt']]  = [
                        'pid' => $objLister->id,
                        'lat' => $ort['lat'],
                        'lon' => $ort['lon'],
                        'name' => $ort['name'] . ', ' . $ort['stadt'],
                        'label' => $ort['name'] . ', ' . $ort['stadt'],
                        'quantity' => 0,
                        'tstamp' => time()
                    ];
                }
            }
        }
        $objLister = $this->Database->execute("SELECT pid, ort, COUNT(id) AS quantity FROM tl_news4ward_article WHERE ort <> '' GROUP BY pid, ort");
        while ($objLister->next()) {
            if (isset($arrOrte[$objLister->pid . ', ' . $objLister->ort]))
                $arrOrte[$objLister->pid . ', ' . $objLister->ort]['quantity'] = $objLister->quantity;
        }
        $this->Database->execute("TRUNCATE tl_news4ward_location_cache");
        foreach ($arrOrte as $k => $ort) {
            if ($ort['quantity'] > 0) {
                $ort['href'] = $this->generateFrontendUrl($arrJumpTo[$ort['pid']]->row(),'/ort/'.urlencode($ort['name']));
                $this->Database->prepare("INSERT INTO tl_news4ward_location_cache SET tstamp = ?, pid = ?, name = ?, lat = ?, lon = ?, quantity = ?, label = ?")->execute($ort['tstamp'], $ort['pid'], '<a href="' . $ort['href'] . '">' . $ort['name'] . '</a>', $ort['lat'], $ort['lon'], $ort['quantity'], $ort['label']);
            }
        }
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\Contao\Input::get('cid'))
        {
            $this->toggleVisibility(\Contao\Input::get('cid'), (\Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;id=' . \Contao\Input::get('id') . '&amp;cid=' . urlencode($row['id']) . '&amp;state=' . $row['protected'];
        $visible = $this->Database->prepare("SELECT protected FROM tl_news4ward_article WHERE id = ?")->execute(html_entity_decode(urldecode($row['id'])))->next()->protected;
        $icon = $visible ? 'invisible.svg' : 'visible.svg';


        return '<a href="' . $this->addToUrl($href) . '" title="' . \Contao\StringUtil::specialchars($title) . '" data-tid="cid"' . $attributes . '>' . \Contao\Image::getHtml($icon, $label, 'data-state="' . ($row['protected'] ? 0 : 1) . '"') . '</a> ';
    }

    /**
     * Fetch all categories for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getCategories($dc)
    {
        $this->import('Database');
        $arrCategories = array();
        $categories = $this->Database->prepare('SELECT categories FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $categories = deserialize($categories->categories,true);
        foreach($categories as $v)
        {
            $arrCategories[] = $v['category'];
        }
        return $arrCategories;
    }

    /**
     * Fetch all cameras for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getCameras($dc)
    {
        $this->import('Database');
        $arrCameras = array();
        $cameras = $this->Database->prepare('SELECT cameras FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $cameras = deserialize($cameras->cameras,true);
        foreach($cameras as $v)
        {
            $arrCameras[] = $v['camera'];
        }
        return $arrCameras;
    }

    /**
     * Fetch all softwares for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getSoftwares($dc)
    {
        $this->import('Database');
        $arrSoftwares = array();
        $softwares = $this->Database->prepare('SELECT software FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $softwares = deserialize($softwares->software,true);
        foreach($softwares as $v)
        {
            $arrSoftwares[] = $v['software'];
        }
        return $arrSoftwares;
    }

    /**
     * Fetch all fotografen for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getFotografen($dc)
    {
        $this->import('Database');
        $arrFotografen = array();
        $fotografen = $this->Database->prepare('SELECT fotografen FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $fotografen = deserialize($fotografen->fotografen,true);
        foreach($fotografen as $v)
        {
            $arrFotografen[] = $v['name'];
        }
        return $arrFotografen;
    }

    /**
     * Fetch all comodels for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getCoModels($dc)
    {
        $this->import('Database');
        $arrComodels = array();
        $comodels = $this->Database->prepare('SELECT comodels FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $comodels = deserialize($comodels->comodels,true);
        foreach($comodels as $v)
        {
            $arrComodels[] = $v['name'];
        }
        return $arrComodels;
    }

    /**
     * Fetch all orte for the current archive
     * @param Data_Container $dc
     * @return array
     */
    public function getOrte($dc)
    {
        $this->import('Database');
        $arrOrte = array();
        $orte = $this->Database->prepare('SELECT orte FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
        $orte = deserialize($orte->orte,true);
        foreach($orte as $v)
        {
            $arrOrte[] = $v['name'] . ', ' . $v['stadt'];
        }
        return $arrOrte;
    }

    public function toggleVisibility($intId, $blnVisible, \Contao\DataContainer $dc=null)
    {
        // Set the ID and action
        $intId = html_entity_decode($intId);
        $id = $this->Database->prepare("SELECT id FROM tl_news4ward_article WHERE path = ?")->execute($intId)->next()->id;
        \Contao\Input::setGet('id', $id);
        \Contao\Input::setGet('act', 'toggle');
        if (!$dc) {
            $dc = new \Contao\DC_Table('tl_news4ward_article');
        }
        if ($dc)
        {
            $dc->id = $id; // see #8043
        }

        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_news4ward_article']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_news4ward_article']['config']['onload_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        // Set the current record
        if ($dc)
        {
            $objRow = $this->Database->prepare("SELECT * FROM tl_news4ward_article WHERE id=?")
                ->limit(1)
                ->execute($dc->id);

            if ($objRow->numRows)
            {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new \Contao\Versions('tl_news4ward_article', $intId);
        $objVersions->initialize();

        // Reverse the logic (elements have invisible=1)
        $blnVisible = !$blnVisible;

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_news4ward_article']['fields']['protected']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_news4ward_article']['fields']['protected']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();
        // Update the database
        $this->Database->prepare("UPDATE tl_news4ward_article SET tstamp=$time, protected='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($dc->id);
        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->protected = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_news4ward_article']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_news4ward_article']['config']['onsubmit_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }

	/**
	 * Generate listItem
	 * @param array
	 * @return string
	 */
	public function listItem($arrRow)
	{
		// the title
		$strReturn = ' <div style="font-weight:bold;margin-bottom:5px;line-height:18px;height:18px;">'.$this->generateImage('articles.gif','','style="vertical-align:bottom;"').' '.$arrRow['title'].'</div>';

		// show the autor
		if(!empty($arrRow['author']))
		{
			if(!isset(self::$authorCache[$arrRow['author']]))
			{
				$objAuthor = $this->Database->prepare('SELECT name FROM tl_user WHERE id=?')->execute($arrRow['author']);
				if($objAuthor->numRows)
				{
					self::$authorCache[$arrRow['author']] = $objAuthor->name;
				}
				else
				{
					self::$authorCache[$arrRow['author']] = false;
				}
			}
			if(self::$authorCache[$arrRow['author']])
			{
				$strReturn .= '<div style="color:#999;margin-bottom:5px;">'.$GLOBALS['TL_LANG']['tl_news4ward_article']['author'][0].': '.self::$authorCache[$arrRow['author']].'</div>';
			}
		}

		// generate the status icons
		$strReturn .= '<div style="margin-bottom:5px;">'.$GLOBALS['TL_LANG']['tl_news4ward_article']['status'][0].': ';
		$strReturn .= '<a href="#" onclick="News4ward.showStatusToggler(this,\''.$arrRow['id'].'\', event || window.event); return false;">';
		if($arrRow['status'] == 'draft')
		{
			$strReturn .= $this->generateImage(	'system/modules/news4ward/assets/draft.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']].'"');
		}
		else if($arrRow['status'] == 'review')
		{
			$strReturn .= $this->generateImage('system/modules/news4ward/assets/review.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']].'"');
		}
		else
		{
			$published = ($arrRow['status'] == 'published' && ($arrRow['start'] == '' || $arrRow['start'] < time()) && ($arrRow['stop'] == '' || $arrRow['stop'] > time()));
			$strReturn .= $this->generateImage('system/modules/news4ward/assets/'.($published ? '' : 'not').'published.png','','');
		}
		$strReturn .= '</a>';

		// generate the status toggler popup
		$strReturn .= '<div class="news4wardStatusToggler">';
		foreach($GLOBALS['TL_DCA']['tl_news4ward_article']['fields']['status']['options'] as $status)
		{
			$strReturn .= '<a href="#" onclick="News4ward.setStatus(this,\''.$arrRow['id'].'\',\''.$status.'\', event || window.event); return false;">';
			$strReturn .= $this->generateImage(	'system/modules/news4ward/assets/'.$status.'.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status].'"');
			$strReturn .= ' '.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status];
			$strReturn .= '</a>';
		}

		$strReturn .= '</div>';

		if($arrRow['highlight'])
		{
			$strReturn .= ' '.$this->generateImage('system/modules/news4ward/assets/highlight.png',$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'][0],'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'][0].'"');
		}
		if($arrRow['sticky'])
		{
			$strReturn .= ' '.$this->generateImage('system/modules/news4ward/assets/sticky.png',$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'][0],'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'][0].'"');
		}
		$strReturn .= '</div>';

		// generate start / end date
		$strReturn .= '<div style="color:#999;">';
		$strReturn .= $GLOBALS['TL_LANG']['tl_news4ward_article']['start'][0].': '.$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'],$arrRow['start']);
		if(!empty($arrRow['stop'])) $strReturn .= ' <br> '	.$GLOBALS['TL_LANG']['tl_news4ward_article']['stop'][0].': '.$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'],$arrRow['stop']);
		$strReturn .= '</div>';

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem']) && is_array($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem']))
		{
			foreach ($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem'] as $callback)
			{
				$this->import($callback[0]);
				$strReturn = $this->{$callback[0]}->{$callback[1]}($strReturn, $arrRow);
			}
		}

		return $strReturn;
	}

	/**
	 * Auto-generate an article alias if it has not been set yet
	 *
	 * @param $varValue
	 * @param DataContainer $dc
	 * @throws Exception
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_news4ward_article WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}

	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin && count(preg_grep('/^tl_news4ward_article::/', $this->User->alexf)) < 1)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Check permissions to edit table tl_news4ward_article
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			// allow admins
			return;
		}

		// set rootIDs if the user is only allowed to edit his own articles
		if(is_array($this->User->news4ward_itemRights) && in_array('onlyOwn',$this->User->news4ward_itemRights))
		{
			$objArticles = $this->Database->prepare('SELECT id FROM tl_news4ward_article WHERE author=?')->execute($this->User->id);
			$GLOBALS['TL_DCA']['tl_news4ward_article']['list']['sorting']['root'] = $objArticles->numRows ? $objArticles->fetchEach('id') : array(0);

			// check single-edit
			if($this->Input->get('act')
				&& !in_array($this->Input->get('act'),array('create','select','editAll','overrideAll'))
				&& !in_array($this->Input->get('id'),$GLOBALS['TL_DCA']['tl_news4ward_article']['list']['sorting']['root'])
			)
			{
				$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward article ID "'.$this->Input->get('id').'"', 'tl_news4ward_article checkPermission', TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}

			// check multiple-edit
			if($this->Input->get('act') && in_array($this->Input->get('act'),array('create','select','editAll','overrideAll'))
			)
			{
				$IDS = $this->Session->get('CURRENT');
				$IDS = $IDS['IDS'];
				if(count(array_diff($IDS,$GLOBALS['TL_DCA']['tl_news4ward_article']['list']['sorting']['root'])))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward article IDs "'.implode(',',array_diff($IDS,$GLOBALS['TL_DCA']['tl_news4ward_article']['list']['sorting']['root'])).'"', 'tl_news4ward_article checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
			}
		}

		// find tl_news4archiv.id
		if(!$this->Input->get('act') || in_array($this->Input->get('act'),array('create','select','editAll','overrideAll')))
		{
			$news4wardID = $this->Input->get('id');
		}
		else
		{
			$objArticle = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($this->Input->get('id'));
			$news4wardID = $objArticle->pid;
		}

		// check archive rights
		if(is_array($this->User->news4ward) && count($this->User->news4ward) > 0 && in_array($news4wardID,$this->User->news4ward)) return;

		$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward archive ID "'.$news4wardID.'"', 'tl_news4ward_article checkPermission', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}

    /**
	 * Check for modified news feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('news4ward_feed_updater');

		if (!is_array($session) || count($session) < 1)
		{
			return;
		}

		$this->import('MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper','Helper');

		foreach ($session as $id)
		{
			$this->Helper->generateFeed($id);
		}

		$this->Session->set('news4ward_feed_updater', NULL);
	}

	/**
	 * Schedule a news feed update
	 *
	 * This method is triggered when a single news archive or multiple news
	 * archives are modified (edit/editAll).
	 *
	 * @param \DataContainer $dc
	 * @return void
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no PID
		if (!$dc->activeRecord->pid)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('news4ward_feed_updater');
		$session[] = $dc->activeRecord->pid;
		$this->Session->set('news4ward_feed_updater', array_unique($session));
	}
}

