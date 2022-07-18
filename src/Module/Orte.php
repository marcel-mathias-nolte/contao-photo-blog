<?php

namespace MarcelMathiasNolte\ContaoPhotoBlogBundle\Module;

class Orte extends Module
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_orte';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### PhotoBlog Orte ###';
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

		if (count($this->Template->orte) == 0)
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
        $arrOrte = [];
        $arrOrteMap = [];
        $arrOrteMap2 = [];
        $objOrteLister = $this->Database->execute("SELECT orte FROM tl_news4ward WHERE id IN (".implode(',',$this->news_archives).")");
        while ($objOrteLister->next()) {
            $orte = deserialize($objOrteLister->orte, true);
            if (is_array($orte) && count($orte) > 0) foreach ($orte as $ort) {
                if (!isset($arrOrte[$ort['stadt']])) {
                    $arrOrte[$ort['stadt']] = ['items' => [], 'count' => 0, 'href' => ''];
                }
                $arrOrte[$ort['stadt']]['items'][$ort['name']] = ['count' => 0, 'href' => ''];
                $arrOrteMap[$ort['name'] . ', ' . $ort['stadt']] = $ort['stadt'];
                $arrOrteMap2[$ort['name'] . ', ' . $ort['stadt']] = $ort['name'];
            }
        }
		$objOrte = $this->Database->execute('
            SELECT 
                ort, 
                count(id) as quantity 
            FROM 
                tl_news4ward_article
            WHERE 
                tl_news4ward_article.pid IN ('.implode(',',$this->news_archives).') AND 
                ort <> ""
            GROUP BY 
                ort');

		// just return if on empty result
		if(!$objOrte->numRows)
		{
			$this->Template->orte = array();
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

		while($objOrte->next())
		{
			$arr = $objOrte->row();
            $arrOrte[$arrOrteMap[$arr['ort']]]['count'] += $arr['quantity'];
            if (!$arrOrte[$arrOrteMap[$arr['ort']]]['href']) {
                $arrOrte[$arrOrteMap[$arr['ort']]]['href'] = $this->generateFrontendUrl($objJumpTo->row(),'/stadt/'.urlencode($arrOrteMap[$arr['ort']]));
                $arrOrte[$arrOrteMap[$arr['ort']]]['active'] = (urldecode($this->Input->get('stadt')) === $arrOrteMap[$arr['ort']]);
            }
            $arrOrte[$arrOrteMap[$arr['ort']]]['items'][$arrOrteMap2[$arr['ort']]]['count'] = $arr['quantity'];
            $arrOrte[$arrOrteMap[$arr['ort']]]['items'][$arrOrteMap2[$arr['ort']]]['href'] = $this->generateFrontendUrl($objJumpTo->row(),'/ort/'.urlencode($objOrte->ort));
            $arrOrte[$arrOrteMap[$arr['ort']]]['items'][$arrOrteMap2[$arr['ort']]]['active'] = (urldecode($this->Input->get('ort')) === $objOrte->ort);
		}
		foreach ($arrOrte as $k => $ort) {
		    if ($ort['count'] == 0)
		        unset($arrOrte[$k]);
        }
		uasort($arrOrte, function($b, $a) {
            return intval($a['count']) <=> intval($b['count']);
        });
		if (count($arrOrte) > 0) foreach ($arrOrte as $k => $orte) {
		    $items = $orte['items'];
            foreach ($items as $k2 => $ort2) {
                if ($ort2['count'] == 0)
                    unset($items[$k2]);
            }
            uasort($items, function($b, $a) {
                return intval($a['count']) <=> intval($b['count']);
            });
            $arrOrte[$k]['items'] = $items;
        }
		$this->Template->orte = $arrOrte;
	}


}
