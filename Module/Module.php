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


namespace Psi\News4ward\Module;

abstract class Module extends \Module
{

	/**
	 * Return the meta fields of a news article as array
	 * @param array $arrArticle
	 * @return array
	 */
	protected function getMetaFields($arrArticle)
	{
		$meta = deserialize($this->news4ward_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = \Date::parse($GLOBALS['objPage']->dateFormat, $arrArticle['start']);
					break;

				case 'datetime':
					$return['datetime'] = \Date::parse($GLOBALS['objPage']->datimFormat, $arrArticle['start']);
					break;

				case 'author':
					if (strlen($arrArticle['author']))
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $arrArticle['author'];
					}
					break;
			}
		}

		return $return;
	}


	/**
	 * Parse one or more items and return them as array
	 *
	 * @param array $arrArticles
	 * @param bool|Template $objTemplate
	 * @return array
	 */
	protected function parseArticles($arrArticles, $objTemplate=false)
	{
		if (!$arrArticles)
		{
			return array();
		}

		global $objPage;
		$this->import('\News4ward\Helper','Helper');

		$limit = count($arrArticles);
		$count = 0;
		$arrReturn = array();

		foreach ($arrArticles as $article)
		{
			// init FrontendTemplate if theres no object given
			if (!$objTemplate)
			{
				$objTemplate = new \FrontendTemplate($this->news4ward_template);
			}
			$objTemplate->setData($article);

			$cssID = deserialize($article['cssID'], true);
			$objTemplate->count = ++$count;
			$objTemplate->class = ($cssID && strlen($cssID[1]) ? ' ' .$cssID[1] : '')
									. (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '')
									. ((($count % 2) == 0) ? ' odd' : ' even')
									. ($article['highlight'] ? ' highlight' : '');
			$objTemplate->cssID = ($cssID && $cssID[0]) ? ' id="'.$cssID[0].'"' : '';
			$objTemplate->link = $this->Helper->generateUrl($article);
			$objTemplate->archive = $article['archive'];

			$teaserCssID = deserialize($article['teaserCssID'], true);
			$objTemplate->teaserCssID = ($teaserCssID && $teaserCssID[0]) ? ' id="'.$teaserCssID[0].'"' : '';
			$objTemplate->teaserClass = ($teaserCssID && $teaserCssID[1]) ? $teaserCssID[1] : '';


			// Clean the RTE output for the TEASER
			if ($article['teaser'] != '')
			{
				if ($objPage->outputFormat == 'xhtml')
				{
					$article['teaser'] = \StringUtil::toXhtml($article['teaser']);
				}
				else
				{
					$article['teaser'] = \StringUtil::toHtml5($article['teaser']);
				}

				$objTemplate->teaser = \StringUtil::encodeEmail($article['teaser']);
			}


			// Generate ContentElements
			$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND ptable="tl_news4ward_article" ' . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . ' ORDER BY sorting ')->execute($article['id']);
			$strContent = '';
			while ($objContentelements->next())
			{
				$strContent .= $this->getContentElement($objContentelements->id);
			}

			// Clean the RTE output for the CONTENT
			if ($strContent != '')
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$strContent = \StringUtil::toXhtml($strContent);
				}
				else
				{
					$strContent = \StringUtil::toHtml5($strContent);
				}

				$strContent = \StringUtil::encodeEmail($strContent);
			}

			$objTemplate->content = $strContent;


			// Add meta information
			$arrMeta = $this->getMetaFields($article);
			$objTemplate->date = isset($arrMeta['date']) ? $arrMeta['date'] : '';
			$objTemplate->hasMetaFields = isset($arrMeta) && is_array($arrMeta) && count($arrMeta) ? true : false;
			$objTemplate->timestamp = isset($article['start']) ? $article['start'] : '';
			$objTemplate->author = isset($arrMeta['author']) ? $arrMeta['author'] : '';
			$objTemplate->datetime = isset($arrMeta['datetime']) ? $arrMeta['datetime'] : '';

			// Resolve ID from database driven filesystem
			if ($article['teaserImage'] && ($objImage = \FilesModel::findByPk($article['teaserImage'])) !== null)
			{
				$article['teaserImage'] = $objImage->path;
			}
			else
			{
                $article['teaserImage'] = '';
			}

			// Add teaser image
			if ($article['teaserImage'] && is_file(TL_ROOT.'/'.$article['teaserImage']))
			{
				$imgSize = deserialize($this->imgSize, true);
				$objTemplate->arrSize = $imgSize;
				if(count($imgSize)>1)
				{
					$objTemplate->teaserImage = \Image::get($article['teaserImage'], $imgSize[0], $imgSize[1], $imgSize[2]);
				}
				else
				{
					$objTemplate->teaserImage = $article['teaserImage'];
				}
				$objTemplate->teaserImageRaw = $objTemplate->teaserImag;
			} else {
                $objTemplate->teaserImage = '';
            }

            // Resolve ID from database driven filesystem
            if ($article['showGallery'])
            {
                $article['multiSRC'] = \Contao\StringUtil::deserialize($article['multiSRC']);
                if (!empty($article['multiSRC']) && \is_array($article['multiSRC']))
                {
                    $objFiles = \Contao\FilesModel::findMultipleByUuids($article['multiSRC']);
                    if ($objFiles !== null) {
                        $images = array();
                        $videos = array();
                        $auxDate = array();
                        if (!$article['protected']) {
                            while ($objFiles->next()) {
                                // Continue if the files has been processed or does not exist
                                if (isset($images[$objFiles->path]) || !file_exists(\Contao\System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFiles->path)) {
                                    continue;
                                }

                                // Single files
                                if ($objFiles->type == 'file') {
                                    if ($objFiles->extension == 'mp4') {
                                        $objFile = new \Contao\File($objFiles->path);
                                        $poster = substr($objFile->path, 0, strlen($objFile->path) - strlen($objFile->extension)) . 'png';
                                        if (!file_exists(TL_ROOT . '/' . $poster))
                                            $poster = null;
                                        $videos[$objFiles->path] = array
                                        (
                                            'id' => $objFiles->id,
                                            'uuid' => $objFiles->uuid,
                                            'name' => $objFile->basename,
                                            'singleSRC' => $objFiles->path,
                                            'filesModel' => $objFiles->current(),
                                            'poster' => $poster,
                                            'isHidden' => !FE_USER_LOGGED_IN && $objFiles->hidden
                                        );
                                        continue;
                                    }
                                    $newPath = \MarcelMathiasNolte\ContaoHideFilesBundle\DcaCallbacks::getBlurredSrc($objFiles);
                                    if (!$newPath) {
                                        continue;
                                    } elseif ($newPath != $objFiles->path) {
                                        $objFiles->path = $newPath;
                                    }

                                    $objFile = new \Contao\File($objFiles->path);

                                    if (!$objFile->isImage) {
                                        continue;
                                    }

                                    // Add the image
                                    $images[$objFiles->path] = array
                                    (
                                        'id' => $objFiles->id,
                                        'uuid' => $objFiles->uuid,
                                        'name' => $objFile->basename,
                                        'singleSRC' => $objFiles->path,
                                        'filesModel' => $objFiles->current(),
                                        'isHidden' => !FE_USER_LOGGED_IN && $objFiles->hidden
                                    );

                                    $auxDate[] = $objFile->mtime;
                                } // Folders
                                else {
                                    $objSubfiles = \Contao\FilesModel::findByPid($objFiles->uuid, array('order' => 'name'));

                                    if ($objSubfiles === null) {
                                        continue;
                                    }

                                    while ($objSubfiles->next()) {
                                        // Skip subfolders
                                        if ($objSubfiles->type == 'folder') {
                                            continue;
                                        }

                                        if ($objSubfiles->extension == 'mp4') {
                                            $objFile = new \Contao\File($objSubfiles->path);
                                            $poster = substr($objFile->path, 0, strlen($objFile->path) - strlen($objFile->extension)) . 'png';
                                            if (!file_exists(TL_ROOT . '/' . $poster))
                                                $poster = null;
                                            $videos[$objSubfiles->path] = array
                                            (
                                                'id' => $objSubfiles->id,
                                                'uuid' => $objSubfiles->uuid,
                                                'name' => $objFile->basename,
                                                'singleSRC' => $objSubfiles->path,
                                                'filesModel' => $objSubfiles->current(),
                                                'poster' => $poster,
                                                'isHidden' => !FE_USER_LOGGED_IN && $objSubfiles->hidden
                                            );
                                            continue;
                                        }
                                        $newPath = \MarcelMathiasNolte\ContaoHideFilesBundle\DcaCallbacks::getBlurredSrc($objSubfiles);;
                                        if (!$newPath) {
                                            continue;
                                        } elseif ($newPath != $objSubfiles->path) {
                                            $objSubfiles->path = $newPath;
                                        }

                                        $objFile = new \Contao\File($objSubfiles->path);

                                        if (!$objFile->isImage) {
                                            continue;
                                        }

                                        // Add the image
                                        $images[$objSubfiles->path] = array
                                        (
                                            'id' => $objSubfiles->id,
                                            'uuid' => $objSubfiles->uuid,
                                            'name' => $objFile->basename,
                                            'singleSRC' => $objSubfiles->path,
                                            'filesModel' => $objSubfiles->current(),
                                            'isHidden' => !FE_USER_LOGGED_IN && $objSubfiles->hidden
                                        );

                                        $auxDate[] = $objFile->mtime;
                                    }
                                }
                            }
                            if ($article['orderSRC']) {
                                $tmp = \Contao\StringUtil::deserialize($article['orderSRC']);

                                if (!empty($tmp) && \is_array($tmp)) {
                                    // Remove all values
                                    $arrOrder = array_map(static function () {
                                    }, array_flip($tmp));

                                    // Move the matching elements to their position in $arrOrder
                                    foreach ($images as $k => $v) {
                                        if (\array_key_exists($v['uuid'], $arrOrder)) {
                                            $arrOrder[$v['uuid']] = $v;
                                            unset($images[$k]);
                                        }
                                    }

                                    // Append the left-over images at the end
                                    if (!empty($images)) {
                                        $arrOrder = array_merge($arrOrder, array_values($images));
                                    }

                                    // Remove empty (unreplaced) entries
                                    $images = array_values(array_filter($arrOrder));
                                    unset($arrOrder);
                                }
                            }
                        }
                        $images = array_values($images);

                        $objTemplate->images = $images;

                        $videos = array_values($videos);

                        $objTemplate->videos = $videos;
                    }
                }
            }
            $objTemplate->limit = $article['limit'];

            $props = $this->Database->prepare('SELECT cameras, fotografen, comodels, orte FROM tl_news4ward WHERE id=?')->execute($article['pid']);

            $objTemplate->category = $article['category'];
            $objTemplate->software = $article['software'];
            $cameras = deserialize($props->cameras,true);
            foreach($cameras as $v)
            {
                if ($v['camera'] == $article['camera'])
                {
                    $objTemplate->camera = (object)$v;
                }
            }
            $fotografen = deserialize($props->fotografen,true);
            $fotografen2 = deserialize($article['fotografen'],true);
            $arrFotografen = [];
            foreach($fotografen2 as $v2)
            {
                foreach ($fotografen as $v)
                {
                    if ($v2['fotograf'] == $v['name'])
                    {
                        $arrFotografen[] = (object)$v;
                    }
                }
            }
            $objTemplate->fotografen = $arrFotografen;
            $comodels = deserialize($props->comodels,true);
            $comodels2 = deserialize($article['comodels'],true);
            $arrModels = [];
            foreach($comodels2 as $v2)
            {
                foreach ($comodels as $v)
                {
                    if ($v2['model'] == $v['name'])
                    {
                        $arrModels[] = (object)$v;
                    }
                }
            }
            $objTemplate->comodels = $arrModels;
            $orte = deserialize($props->orte,true);
            foreach($orte as $v)
            {
                if ($v['name'] . ', ' . $v['stadt'] == $article['ort'])
                {
                    $objTemplate->ort = (object)$v;
                }
            }

            // HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['News4wardParseArticle']) && is_array($GLOBALS['TL_HOOKS']['News4wardParseArticle']))
			{
				foreach ($GLOBALS['TL_HOOKS']['News4wardParseArticle'] as $callback)
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($this, $article, $objTemplate, $arrArticles);
				}
			}

			$arrReturn[] = $objTemplate->parse();
		}

		return $arrReturn;
	}



	/**
	 * Sort out protected archives
	 * @param array $arrArchives
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || count($arrArchives) < 1)
		{
			return $arrArchives;
		}

		$this->import('\FrontendUser', 'User');
		$objArchive = $this->Database->execute("SELECT id, protected, `groups` FROM tl_news4ward WHERE id IN(" . implode(',', array_map('intval', $arrArchives)) . ")");
		$arrArchives = array();

		while ($objArchive->next())
		{
			if ($objArchive->protected)
			{
				if (!FE_USER_LOGGED_IN)
				{
					continue;
				}

				$groups = deserialize($objArchive->groups);

				if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
				{
					continue;
				}
			}

			$arrArchives[] = $objArchive->id;
		}

		return $arrArchives;
	}
}
