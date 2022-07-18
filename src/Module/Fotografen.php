<?php

/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward_categories
 * @filesource
 * @licence LGPL
 */

namespace MarcelMathiasNolte\ContaoPhotoBlogBundle\Module;

class Fotografen extends Module
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_fotografen';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### News4ward Fotografen ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news4ward_archives));

		// Return if there are no archives
		if (!is_array($this->news_archives) || count($this->news_archives) < 1)
		{
			return '';
		}

		$strBuffer = parent::generate();

		if (count($this->Template->fotografen) == 0)
		{
			return '';
		}

		return $strBuffer;
	}


	/**
	 * Generate module
	 */
	protected function compile()
    {
        $arrFotografen = [];
		$objFotografen = $this->Database->execute('
            SELECT 
                fotografen
            FROM 
                tl_news4ward_article
            WHERE 
                tl_news4ward_article.pid IN ('.implode(',',$this->news_archives).')');

		// just return if on empty result
		if(!$objFotografen->numRows)
		{
			$this->Template->fotografen = array();
			return;
		}

		// get jumpTo page
		if($this->jumpTo)
		{
			$objJumpTo = $this->Database->prepare('SELECT id,alias FROM tl_page WHERE id=?')->execute($this->jumpTo);
			if(!$objJumpTo->numRows)
				$objJumpTo = $GLOBALS['objPage'];
		}
		else
		{
			$objJumpTo = $GLOBALS['objPage'];
		}

		while($objFotografen->next())
		{
			$fotografen = deserialize($objFotografen->fotografen, true);
			foreach ($fotografen as $fotograf) {
			    isset($arrFotografen[$fotograf['fotograf']]) ? $arrFotografen[$fotograf['fotograf']]['count']++ : $arrFotografen[$fotograf['fotograf']] = ['count' => 1];
                if (!isset($arrFotografen[$fotograf['fotograf']]['href'])) {
                    $arrFotografen[$fotograf['fotograf']]['href'] = $this->generateFrontendUrl($objJumpTo->row(),'/fotograf-in/'.urlencode($fotograf['fotograf']));
                    $arrFotografen[$fotograf['fotograf']]['active'] = (urldecode($this->Input->get('fotograf-in')) === $fotograf['fotograf']);
                }
            }
		}
		uasort($arrFotografen, function($a, $b) {
		    return $a['count'] <=> $b['count'];
        });

		$this->Template->fotografen = $arrFotografen;
	}


}
