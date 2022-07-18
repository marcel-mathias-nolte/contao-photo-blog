<?php


namespace MarcelMathiasNolte\ContaoPhotoBlogBundle;

class OrtHelper extends \Controller
{

	protected static $arrJumpTo = array();

	/**
	 * Return the WHERE-condition if a the url has an ort-parameter
	 * @return bool|string
	 */
	public function ortFilter()
	{
		if(!$this->Input->get('ort')) return false;

		$ort = urldecode($this->Input->get('ort'));

		return array
		(
			'where'  => 'tl_news4ward_article.ort=?',
			'values' => array($ort)
		);
	}

	/**
	 * Return the WHERE-condition if a the url has an ort-parameter
	 * @return bool|string
	 */
	public function fotografFilter()
	{
		if(!$this->Input->get('fotograf-in')) return false;

		$fotograf = urldecode($this->Input->get('fotograf-in'));

		return array
		(
			'where'  => 'tl_news4ward_article.fotografen LIKE ?',
			'values' => array('%"'.$fotograf.'"%')
		);
	}

	/**
	 * Return the WHERE-condition if a the url has an ort-parameter
	 * @return bool|string
	 */
	public function modelFilter()
	{
		if(!$this->Input->get('model')) return false;

		$model = urldecode($this->Input->get('model'));

		return array
		(
			'where'  => 'tl_news4ward_article.comodels LIKE ?',
			'values' => array('%"'.$model.'"%')
		);
	}

	/**
	 * Return the WHERE-condition if a the url has an ort-parameter
	 * @return bool|string
	 */
	public function stadtFilter()
	{
		if(!$this->Input->get('stadt')) return false;

        $this->import('Database');
        $stadt = urldecode($this->Input->get('stadt'));
        $objOrtLister = $this->Database->execute("SELECT orte FROM tl_news4ward");
        $arrSearch = [];
        $arrValues = [];
        while ($objOrtLister->next())
        {
            $orte = deserialize($objOrtLister->orte, true);
            if (is_array($orte) && count($orte) > 0) foreach ($orte as $ort)
            {
                if ($ort['stadt'] == $stadt)
                {
                    $arrValues[] = $ort['name'] . ', ' . $ort['stadt'];
                    $arrSearch[] = 'tl_news4ward_article.ort=?';
                }
            }
        }

        if (count($arrSearch) == 0) return false;
		return array
		(
			'where'  => '(' . implode(' OR ', $arrSearch) . ')',
			'values' => $arrValues
		);
	}


	/**
	 * Add category link to the template
	 *
	 * @param \MarcelMathiasNolte\ContaoPhotoBlogBundle\Module\Module $obj
	 * @param array $arrArticle
	 * @param FrontendTemplate $objTemplate
	 */
	public function ortParseArticle($obj, $arrArticle, $objTemplate)
	{
        if(!isset(self::$arrJumpTo[$arrArticle['pid']])) {
            $this->import('Database');
            $objJumpTo = $this->Database->prepare('SELECT tl_page.*
                                                   FROM tl_page
                                                   LEFT JOIN tl_news4ward ON (tl_page.id=tl_news4ward.jumpToList)
                                                   WHERE tl_news4ward.id=?')
                                        ->execute($arrArticle['pid']);
            if($objJumpTo->numRows) {
                self::$arrJumpTo[$arrArticle['pid']] = $objJumpTo->row();
            } else {
                self::$arrJumpTo[$arrArticle['pid']] = false;
            }
        }
        $arrArticle['location'] = explode(', ', $arrArticle['ort']);
        $city = '';
        $props = $this->Database->prepare('SELECT orte FROM tl_news4ward WHERE id=?')->execute($arrArticle['pid']);
        if ($props->next()) {
            $orte = \deserialize($props->orte, true);
            foreach ($orte as $v) {
                if ($arrArticle['ort'] == $v['name'] . ', ' . $v['stadt']) $city = $v['stadt'];
            }
        }

        $fotografen = [];
        $models = [];
        $fotografen3 = \deserialize($arrArticle['fotografen'], true);
        if (count($fotografen3) > 0) foreach ($fotografen3 as $v) {
            $fotografen[] = $v['fotograf'];
        }
        $models3 = \deserialize($arrArticle['comodels'], true);
        if (count($models3) > 0) foreach ($models3 as $v) {
            $models[] = $v['model'];
        }

        if(self::$arrJumpTo[$arrArticle['pid']]) {
            $objTemplate->ortHref = $this->generateFrontendUrl(self::$arrJumpTo[$arrArticle['pid']], '/ort/'.urlencode($arrArticle['ort']));
            if ($city) $objTemplate->cityHref = $this->generateFrontendUrl(self::$arrJumpTo[$arrArticle['pid']], '/stadt/'.urlencode($city));
            if (count($models) > 0) foreach ($models as $k => $v) {
                $models[$k] = $this->generateFrontendUrl(self::$arrJumpTo[$arrArticle['pid']], '/model/'.urlencode($v));
            }
            $objTemplate->modelHrefs = $models;
            if (count($fotografen) > 0) foreach ($fotografen as $k => $v) {
                $fotografen[$k] = $this->generateFrontendUrl(self::$arrJumpTo[$arrArticle['pid']], '/fotograf-in/'.urlencode($v));
            }
            $objTemplate->fotografenHrefs = $fotografen;
        } else {
            $objTemplate->ortHref = $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/ort/'.urlencode($arrArticle['ort']));
            if ($city) $objTemplate->cityHref = $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/stadt/'.urlencode($city));
            if (count($models) > 0) foreach ($models as $k => $v) {
                $models[$k] = $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/model/'.urlencode($v));
            }
            $objTemplate->modelHrefs = $models;
            if (count($fotografen) > 0) foreach ($fotografen as $k => $v) {
                $fotografen[$k] = $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/fotograf-in/'.urlencode($v));
            }
            $objTemplate->fotografenHrefs = $fotografen;
        }
	}
}

?>