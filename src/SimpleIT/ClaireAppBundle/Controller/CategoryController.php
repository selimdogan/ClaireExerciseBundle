<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


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
        /* Get the categories */
        $categoryRequest = $this->getCategoryRouteService()->getCategory();
        $categories = $this->getApiService()->getResult($categoryRequest);

        /* Prepare view and parameters */
        $this->view = 'SimpleITClaireAppBundle:Category:list.html.twig';
        $this->viewParameters = array(
            'categories' => $categories->getContent()
        );

        return $this->generateView($this->view, $this->viewParameters);
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
        $categorySlug = $request->get('slug');
        $parameters = $request->query->all();

        /* Get the category */
        $categoryRequest = $this->getClaireApi('categories')->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);

        /* Throw 404 if object not found */
        $this->checkObjectFound($category);

        /* get related Tags and courses */
        $requests['tags'] = $this->getClaireApi('categories')->getTags($categorySlug);
        // @FIXME USE THIS REQUEST
        //$requests['courses'] = $this->getClaireApi('courses')->getCoursesByCategory($options);

        $options = new ApiRequestOptions(array('sort'));
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->setFilters($parameters);
        $options->addFilter('category', $categorySlug);

        $requests['courses'] = $this->getClaireApi('courses')->getCourses($options);

        $results = $this->getClaireApi()->getResults($requests);

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

        return $this->generateView($this->view, $this->viewParameters);
    }


    /**
     * View single Tag
     *
     * @param Request $request The request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function viewTagAction(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');
        $parameters = $request->query->all();

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->bindFilter($parameters, array('sort'));

        /* get Tag and associated tags */
        $tagRequest = $this->getClaireApi('categories')->getTag($categorySlug, $tagSlug);
        $tag = $this->getClaireApi()->getResult($tagRequest);

        /* Throw 404 if object not found */
        $this->checkObjectFound($tag);

        $requests['category'] = $this->getClaireApi('categories')->getCategory($categorySlug);
        $requests['courses'] = $this->getClaireApi('categories')->getTagCourses($tagSlug, $options);

        $requests['associated-tags'] = $this->getClaireApi('categories')->getAssociatedTags(
            $categorySlug,
            $tagSlug
        );
        $results = $this->getClaireApi()->getResults($requests);

        if(is_null($results['courses']->getPager()))
        {
            $totalItems = count($results['courses']->getContent());
        }
        else
        {
            $totalItems = $results['courses']->getPager()->getTotalItems();
        }

        /* Prepare view and parameters */
        $this->view = 'TutorialBundle:Category:viewTag.html.twig';
        $this->viewParameters = array(
            'tag' => $tag->getContent(),
            'category' =>  $results['category']->getContent(),
            'associatedTags' => $results['associated-tags']->getContent(),
            'courses' => $results['courses']->getContent(),
            'appPager' => $results['courses']->getPager(),
            'totalItems' =>  $totalItems
        );

        return $this->generateView($this->view, $this->viewParameters);
    }

    /**
     * Generate the rendered response
     *
     * @param string $view           The view name
     * @param array  $viewParameters Associated view parameters
     *
     * @return Response A Response instance
     */
    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
