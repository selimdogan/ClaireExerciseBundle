<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;

/**
 * Category controller
 */
class CategoryController extends BaseController
{
    /**
     * Category list
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function listAction()
    {
        $this->getCategoriesApi()->prepareCategories();
        $categories = $this->getCategoriesApi()->getResult();

        return $this->render('SimpleITClaireAppBundle:Category:list.html.twig', array('categories' => $categories['categories']));
    }

    /**
     * Category list
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function viewAction(Request $request)
    {
        $categorySlug = $request->get('slug');
        $parameters = $request->query->all();
        $parameters['category'] = $categorySlug;

        $this->getCategoriesApi()->prepareCategory($categorySlug);
        $this->getCategoriesApi()->prepareTags($categorySlug);
        $this->getCoursesApi()->prepareCourses($parameters);

        $category = $this->getCategoriesApi()->getResult();
        $courses = $this->getCoursesApi()->getResult();

        return $this->render('SimpleITClaireAppBundle:Category:view.html.twig',
            array(
                'category' => $category['category'],
                'tags' => $category['tags']['tags'],
                'branches' => $courses['branches']['branches']
            )
        );
    }

    public function viewTagsAction(Request $request)
    {
        $categorySlug = $request->get('categorySlug');
        $tagSlug = $request->get('slug');

        $this->getCategoriesApi()->prepareTag($categorySlug, $tagSlug);
        //$this->getCategoriesApi()->prepareAssociatedTags($categorySlug, $tagSlug);

        $tag = $this->getCategoriesApi()->getResult();

        return $this->render('SimpleITClaireAppBundle:Category:viewTag.html.twig',
            array(
                'tag' => $tag['tags']
            )
        );
    }
}
