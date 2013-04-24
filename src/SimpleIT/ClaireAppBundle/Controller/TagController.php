<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Tag;
use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SimpleIT\AppBundle\Services\ApiService;

/**
 * Tag controller
 */
class TagController extends BaseController
{
    /**
     * View single Tag
     *
     * @param Request $request The request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request)
    {
        $this->processViewTag($request);

        return $this->render($this->view, $this->viewParameters);
    }

    protected function processView(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');
        $parameters = $request->query->all();

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->addFilter('sort', 'title asc');
        $options->addFilters($parameters, array('sort'));

        /* get Tag and associated tags */
        $this->tagService = $this->get('simpleit.claire.tag');

        $tag = $this->tagService->getTagWithCourses($categorySlug, $tagSlug, $options);

        if(is_object($tag->getCourses()))
        {
            $totalItems = $tag->getCourses()->getTotalItems();
        }
        else
        {
            $totalItems = count($tag->getCourses());
        }

        /* Prepare view and parameters */
        $this->view = 'TutorialBundle:Category:viewTag.html.twig';
        $this->viewParameters = array(
            'tag' => $tag,
            'category' =>  $tag->getCategory(),
            'associatedTags' => $tag->getAssociatedTags(),
            'courses' => $tag->getCourses(),
            'recommendedCourses' => $tag->getRecommendedCourses(),
            'totalItems' =>  $totalItems
        );
    }
}
