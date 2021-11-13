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


namespace Psi\News4ward;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;
use Psi\News4ward\Model\ArticleModel;
use Dreibein\ContaoPushBundle\Push\PushManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PushHelper extends \Controller
{
    /**
     * @var PushManager
     */
    private $manager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * News constructor.
     *
     * @param PushManager $manager
     */
    public function __construct(PushManager $manager, RequestStack $requestStack, ContaoFramework $framework)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
        $this->framework = $framework;
    }

    public function onLoad($dc)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request->query->get('sendPush')) {
            return;
        }

        $this->framework->initialize();
        $controller = $this->framework->getAdapter(Controller::class);
        $adapter = $this->framework->getAdapter(ArticleModel::class);

        $model = $adapter->findByPk($dc->id);

        $title = $model->title;
        $body = \Soundasleep\Html2Text::convert($model->teaser);
        $url = sprintf(
            '%s/%s',
            $request->getSchemeAndHttpHost(),
            $controller->replaceInsertTags(sprintf('{{news4ward_url::%s}}', $dc->id))
        );

        $this->manager->sendNotification($title, $body, ['url' => $url]);
    }

}
