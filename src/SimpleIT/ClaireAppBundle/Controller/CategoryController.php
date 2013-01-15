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

        /* Get the category */
        $categoryRequest = $this->getClaireApi('categories')->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);

        /* Throw 404 if object not found */
        $this->checkObjectFound($category);

        /* get related Tags and courses */
        $requests['tags'] = $this->getClaireApi('categories')->getTagsByCategory($categorySlug);
        // @FIXME USE THIS REQUEST
        //$requests['courses'] = $this->getClaireApi('courses')->getCoursesByCategory($options);

        $options = new ApiRequestOptions(array('sort'));
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->addFilter('sort', 'title asc');
        $options->addFilters($parameters, array('sort'));
        $options->addFilter('category', $categorySlug);

        $requests['courses'] = $this->getClaireApi('courses')->getCourses($options);

        $results = $this->getClaireApi()->getResults($requests);

        if(is_null($results['courses']) || $results['courses'] === false)
        {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Oups, la liste des tutoriels n\'a pas pu être générée');
        }
        if(is_null($results['tags']) || $results['tags'] === false)
        {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Oups, la liste des tutoriels n\'a pas pu être générée');
        }

        if(is_null($results['courses']->getPager()))
        {
            $totalItems = count($results['courses']->getContent());
        }
        else
        {
            $totalItems = $results['courses']->getPager()->getTotalItems();
        }

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:view.html.twig';
        $this->viewParameters = array(
            'category' => $category->getContent(),
            'tags' => $results['tags']->getContent(),
            'courses' => $results['courses']->getContent(),
            'appPager' => $results['courses']->getPager(),
            'totalItems' =>  $totalItems
        );
    }
}
