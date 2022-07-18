<?php

namespace MarcelMathiasNolte\ContaoPhotoBlogBundle\Module;

class Models extends Module
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_models';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### News4ward Models ###';
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

		if (count($this->Template->models) == 0)
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
        $arrModels = [];
		$objModels = $this->Database->execute('
            SELECT 
                comodels
            FROM 
                tl_news4ward_article
            WHERE 
                tl_news4ward_article.pid IN ('.implode(',',$this->news_archives).')');

		// just return if on empty result
		if(!$objModels->numRows)
		{
			$this->Template->models = array();
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

		while($objModels->next())
		{
			$comodels = deserialize($objModels->comodels, true);
			foreach ($comodels as $model) {
			    isset($arrModels[$model['model']]) ? $arrModels[$model['model']]['count']++ : $arrModels[$model['model']] = ['count' => 1];
                if (!isset($arrModels[$model['model']]['href'])) {
                    $arrModels[$model['model']]['href'] = $this->generateFrontendUrl($objJumpTo->row(),'/model/'.urlencode($model['model']));
                    $arrModels[$model['model']]['active'] = (urldecode($this->Input->get('model')) === $model['model']);
                }
            }
		}
		uasort($arrModels, function($a, $b) {
		    return $a['count'] <=> $b['count'];
        });

		$this->Template->models = $arrModels;
	}


}
