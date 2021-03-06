<?php

namespace MarcelMathiasNolte\ContaoPhotoBlogBundle\Module;

class Reader extends Module
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_reader';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### PhotoBlog READER ###';
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

		// set the template
		if ($this->news4ward_readerTemplate != '')
		{
			$this->strTemplate = $this->news4ward_readerTemplate;
		}

        return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
    {
		$this->import('MarcelMathiasNolte\ContaoPhotoBlogBundle\Helper', 'Helper');

		/* build where */
		$where = array();
		$whereVals = array();
		$time = time();

		// news archives
		$where[] = 'FIND_IN_SET(tl_news4ward_article.pid, ?)';
		$whereVals[] = implode(',', array_map('intval', $this->news_archives));

		// published
		if (!BE_USER_LOGGED_IN)
		{
			$where[] = "(tl_news4ward_article.start='' OR tl_news4ward_article.start<?) AND (tl_news4ward_article.stop='' OR tl_news4ward_article.stop>?) AND tl_news4ward_article.status='published'";
			$whereVals[] = $time;
			$whereVals[] = $time;
		}

		// alias
		$varAlias = ($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array('items', $GLOBALS['TL_AUTO_ITEM'])) ? \Input::get('auto_item') : \Input::get('items');
		$where[] = '(tl_news4ward_article.id=? OR tl_news4ward_article.alias=?)';
		$whereVals[] = is_numeric($varAlias) ? $varAlias : 0;
		$whereVals[] = $varAlias;


		/* get the item */
		$objArticle = $this->Database->prepare("
			SELECT tl_news4ward_article.*, author AS authorId, user.name as author, user.email as authorEmail,
				(SELECT title FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS archive,
				(SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS parentJumpTo
			FROM tl_news4ward_article
			LEFT JOIN tl_user AS user ON (tl_news4ward_article.author=user.id)
			WHERE ".implode(' AND ',$where))->execute($whereVals);


		if (!$objArticle->numRows) {
			header('HTTP/1.1 404 Not Found');
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate(false);
			exit;
		};

		if ($objArticle->protected) {

                $groups = deserialize($objArticle->groups);
                if (!FE_USER_LOGGED_IN || !is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
                {
                    header('HTTP/1.1 401 Authorisation Required');
                    $objHandler = new $GLOBALS['TL_PTY']['error_401']();
                    $objHandler->generate(false);
                    exit;
                }
		}

		$this->parseArticles($objArticle->fetchAllAssoc(), $this->Template);

    // Respect the article-cssID
    $moduleCssID = deserialize($this->cssID, true);
    $articleCssID = deserialize($objArticle->cssID, true);
    if($articleCssID[0] && !$moduleCssID[0]) {
	    // take article cssID only if module doesnt have one
	    $moduleCssID[0] = $articleCssID[0];
    }
    if($articleCssID[1]) {
	    // merge article and module css classes
	    if($moduleCssID[1]) $moduleCssID[1] .= ' ';
	    $moduleCssID[1] .= $articleCssID[1];
    }
    $this->cssID = $moduleCssID;


		// Add social Buttons
		$this->Template->socialButtons = deserialize($objArticle->social,true);

		// Add keywords and description
		if ($objArticle->keywords != '')
		{
			$GLOBALS['TL_KEYWORDS'] .= (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '') . $objArticle->keywords;
		}
		if ($objArticle->description != '')
		{
			$GLOBALS['objPage']->description .= (!empty($GLOBALS['objPage']->description) ? ' ': '') . $objArticle->description;
		}

		// Add Page Title
		$GLOBALS['objPage']->title = $objArticle->title;
		$GLOBALS['objPage']->pageTitle = strlen($objArticle->pageTitle)
			? $objArticle->pageTitle
			: $objArticle->title;

		// Add facebook meta data
		// debug with https://developers.facebook.com/tools/debug
		if ($this->news4ward_facebookMeta)
		{
			$strTagEnding = ($GLOBALS['objPage']->outputFormat == 'xhtml') ? ' />' : '>';

			if($objArticle->useFacebookImage && ($objImage = \FilesModel::findByPk($objArticle->facebookImage)) && is_file(TL_ROOT.'/'.$objImage->path))
			{
				$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="'.$this->Environment->base.$objImage->path.'"'.$strTagEnding;
			}
			else if($objArticle->teaserImage && ($objImage = \FilesModel::findByPk($objArticle->teaserImage)) && is_file(TL_ROOT.'/'.$objImage->path))
			{
				$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="'.$this->Environment->base.$objImage->path.'"'.$strTagEnding;
			}
			$GLOBALS['TL_HEAD'][] = '<meta property="og:title" content="'.$objArticle->title.'"'.$strTagEnding;
			$GLOBALS['TL_HEAD'][] = '<meta property="og:url" content="'.$this->Environment->base.$this->Environment->request.'"'.$strTagEnding;
			$GLOBALS['TL_HEAD'][] = '<meta property="og:description" content="'.str_replace('"','\'',(($objArticle->description) ? $objArticle->description : strip_tags($objArticle->teaser))).'"'.$strTagEnding;
		}

		// find NEXT  article
		$strWhere = '';
		if (!BE_USER_LOGGED_IN)
		{
			$strWhere = "AND (a.start='' OR a.start<".$time.") AND (a.stop='' OR a.stop>".$time.") AND a.status='published'";
		}

		$objNextArticle = $this->Database->prepare("
			SELECT a.id, a.alias, a.title, a.pid ". //, (SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=a.pid) AS parentJumpTo
			"FROM tl_news4ward_article AS a
			WHERE FIND_IN_SET(a.pid, ?) AND a.start > ?".$strWhere.' ORDER BY start ASC')->limit(1)->execute(implode(',', array_map('intval', $this->news_archives)), $objArticle->start);

		if ($objNextArticle->numRows)
		{
			$arrNext						= $objNextArticle->row();
			$arrNext['parentJumpTo']		= $GLOBALS['objPage']->id;
			$arrNext['href']				= $this->Helper->generateUrl($arrNext);
			$this->Template->nextArticle	= $arrNext;
		}
		else
		{
			$this->Template->nextArticle = false;
		}

		// find PREVIOUS article
		$objPrevArticle = $this->Database->prepare("
			SELECT a.id, a.alias, a.title, a.pid ". //, (SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=a.pid) AS parentJumpTo
			"FROM tl_news4ward_article AS a
			WHERE FIND_IN_SET(a.pid, ?) AND a.start < ?".$strWhere.' ORDER BY start DESC')->limit(1)->execute(implode(',', array_map('intval', $this->news_archives)), $objArticle->start);

		if ($objPrevArticle->numRows)
		{
			$arrPrev						= $objPrevArticle->row();
			$arrPrev['parentJumpTo']		= $GLOBALS['objPage']->id;
			$arrPrev['href']				= $this->Helper->generateUrl($arrPrev);
			$this->Template->prevArticle	= $arrPrev;
		}
		else
		{
			$this->Template->prevArticle = false;
		}
	}
}
