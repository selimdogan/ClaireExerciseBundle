<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SimpleIT\AppBundle\Services\ApiService;

/**
 * Category controller
 */
class CategoryController extends BaseController
{
    /**
     * View the Categories list
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $this->prepareListAction();

        return $this->render($this->view, $this->viewParameters);
    }

    private function prepareListAction()
    {
        /* Get the categories */
        $categoryRequest = $this->getCategoryRouteService()->getCategory();
        $categories = $this->getApiService()->getResult($categoryRequest);

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:list.html.twig';
        $this->viewParameters = array(
            'categories' => $categories->getContent()
        );
    }


    /**
     * View a single category
     *
     * @param Request $request The request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request)
    {
        $this->processView($request);

        return $this->render($this->view, $this->viewParameters);
    }

    protected function processView(Request $request)
    {
        $categorySlug = $request->get('slug');
        $parameters = $request->query->all();

        $options = new ApiRequestOptions(array('sort'));
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->addFilter('sort', 'title asc');
        $options->addFilters($parameters, array('sort'));
        $options->addFilter('category', $categorySlug);

        /* Get the category */
        $this->categoryService = $this->get('simpleit.claire.category');

        $category = $this->categoryService->getCategoryWithCourses($categorySlug, $options);

        $totalItems = count($category->getCourses());

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:view.html.twig';
        $this->viewParameters = array(
            'category' => $category,
            'tags' => $category->getTags(),
            'courses' => $category->getCourses(),
            'totalItems' =>  $totalItems
        );
    }
}
