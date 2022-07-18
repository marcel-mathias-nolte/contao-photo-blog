<?php


namespace MarcelMathiasNolte\ContaoPhotoBlogBundle\Elements;
use Dirch\masonry\Masonry;

/**
 * Implements the frontend interface
 */

class ContentNews4WardContent extends \Contao\ContentElement {

	protected $strTemplate = 'ce_news4ward_content';

	protected $strTemplateJs = 'js_masonry';

	protected $objPost = null;

	public function generate() {
		if ($this->ptable == 'tl_news4ward_article') {
			$this->objPost = \MarcelMathiasNolte\ContaoPhotoBlogBundle\Model\ArticleModel::findByPk($this->pid);
		}
        $request = \System::getContainer()->get('request_stack')->getCurrentRequest();
        if ($request && \System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {

			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->title = $GLOBALS['TL_LANG']['CTE']['news4ward_content'][0];
			$objTemplate->wildcard = $this->objPost == null ? 'ungültiger Bezug / invalid reference' : $this->objPost->title;
			return $objTemplate->parse();
		}
		return $this->objPost == null ? '' : parent::generate();
	}

	public function compile() {
		$this->Template->fields = deserialize($this->news4ward_fields, true);
		$this->Template->post = $this->objPost;

		if ($this->objPost->showGallery)
		{
			$this->objPost->multiSRC = \Contao\StringUtil::deserialize($this->objPost->multiSRC);
			if (!empty($this->objPost->multiSRC) && \is_array($this->objPost->multiSRC))
			{
				$objFiles = \Contao\FilesModel::findMultipleByUuids($this->objPost->multiSRC);
				if ($objFiles !== null) {
					$images = array();
					$videos = array();
					$video_posters = array();
					$auxDate = array();
					//if (!$this->objPost->protected) {
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
									if (!file_exists(\Contao\System::getContainer()->getParameter('kernel.project_dir') . '/' . $poster))
										$poster = null;
									else
										$video_posters[] = $poster;
									$videos[$objFiles->path] = array
									(
										'id' => $objFiles->id,
										'uuid' => $objFiles->uuid,
										'name' => $objFile->basename,
										'singleSRC' => $objFiles->path,
										'filesModel' => $objFiles->current(),
										'poster' => $poster
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
									'filesModel' => $objFiles->current()
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
                                        if (!file_exists(\Contao\System::getContainer()->getParameter('kernel.project_dir') . '/' . $poster))
                                            $poster = null;
										else
											$video_posters[] = $poster;
                                        $videos[$objSubfiles->path] = array
                                        (
                                            'id' => $objSubfiles->id,
                                            'uuid' => $objSubfiles->uuid,
                                            'name' => $objFile->basename,
                                            'singleSRC' => $objSubfiles->path,
                                            'filesModel' => $objSubfiles->current(),
                                            'poster' => $poster
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
										'filesModel' => $objSubfiles->current()
									);

									$auxDate[] = $objFile->mtime;
								}
							}
						}
						foreach ($images as $path => $image) {
							if (in_array($path, $video_posters))
								unset($images[$path]);
						}
						shuffle($images);
					}
					$images = array_values($images);
					$this->Template->images = $images;
					$videos = array_values($videos);
					$this->Template->videos = $videos;
					global $objPage;
					if (count($images) > 0 && $objPage != null && $objPage->id != 37) {
						$objTemplateJs = new \FrontendTemplate($this->strTemplateJs);
						$objTemplateJs->id = $this->id;
						$objMasonry = new Masonry();
						$objMasonry->createTemplateData($this->Template, $objTemplateJs);
					}
				//}
			}
		}
	}
}
