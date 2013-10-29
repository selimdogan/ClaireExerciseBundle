<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Type\AssociatedContent\CategoryByCourseType;
use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategoryByCourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryByCourseController extends AppController
{
    /**
     * Add a category to a course (GET)
     *
     * @param int $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createViewAction($courseId)
    {
        // Get course category
        try {
            $category = $this->get('simple_it.claire.associated_content.category')->getByCourse(
                $courseId
            );
        } catch (NotFoundHttpException $nfhe) {
            $category = null;
        }

        $form = $this->createForm(new CategoryByCourseType(), $category);

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Category/Component:editByCourse.html.twig',
            array(
                'courseId' => $courseId,
                'form'     => $form->createView(),
                'action'   =>
                    $this->generateUrl(
                        'simple_it_claire_component_associated_content_category_category_by_course_edit',
                        array('courseId' => $courseId)
                    )
            )
        );
    }

    /**
     * Add a category to a course
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function createAction(Request $request, $courseId)
    {
        $category = new CategoryResource();
        $form = $this->createForm(new CategoryByCourseType(), $category);
        $form->bind($request);
        if ($this->get('validator')->validate($form->getData())) {
            $category = $this->get('simple_it.claire.associated_content.category')->addToCourse(
                $category->getId(),
                $courseId
            );

            return new JsonResponse($category);
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }
    }
}
