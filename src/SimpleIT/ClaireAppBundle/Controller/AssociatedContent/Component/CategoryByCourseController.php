<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Category\GetDraftCourseCategoryResponse;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\GetDraftCourseCategoryRequestDTO;
use
    OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\CategoryByCourse\DTO\SaveCourseCategoryRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\AssociatedContent\Model\CategoryByCourseModel;
use SimpleIT\ClaireAppBundle\Form\AssociatedContent\Type\CategoryByCourseType;
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
    public function editViewAction($courseId)
    {
        try {
            /** @var GetDraftCourseCategoryResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.associated_content_use_case_factory')
                ->make('GetDraftCourseCategory')
                ->execute(new GetDraftCourseCategoryRequestDTO($courseId));

            $form = $this->createForm(
                new CategoryByCourseType(),
                new CategoryByCourseModel($ucResponse->getCategoryId())
            );

            return $this->render(
                'SimpleITClaireAppBundle:AssociatedContent/Category/Component:editByCourse.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_component_associated_content_category_category_by_course_edit',
                            array('courseId' => $courseId)
                        ),
                    'form'      => $form->createView()
                )
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function editAction(Request $request, $courseId)
    {
        $form = $this->createForm(
            new CategoryByCourseType(),
            $category = new CategoryByCourseModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.associated_content_use_case_factory')
                ->make('SaveCourseCategory')->execute(
                    new SaveCourseCategoryRequestDTO($courseId, $category->getCategoryId())
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
