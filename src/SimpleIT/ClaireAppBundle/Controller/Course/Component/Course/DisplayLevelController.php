<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\DisplayLevel\GetDraftDisplayLevelResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO\GetDraftDisplayLevelRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\DisplayLevel\DTO\SaveDisplayLevelRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\DisplayLevelModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\DisplayLevelType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DisplayLevelController extends AppController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editViewAction($courseId)
    {
        try {
            /** @var GetDraftDisplayLevelResponse $ucResponse */
            $ucResponse =
                $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('GetDraftDisplayLevel')
                ->execute(new GetDraftDisplayLevelRequestDTO($courseId));

            $form = $this->createForm(
                new DisplayLevelType(),
                new DisplayLevelModel($ucResponse->getDisplayLevel())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Course/partial:editDisplayLevel.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_course_component_course_display_level_edit',
                            array('courseId' => $courseId)
                        ),
                    'form'      => $form->createView()
                )
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function editAction(Request $request, $courseId)
    {
        $form = $this->createForm(
            new DisplayLevelType(),
            $displayLevel = new DisplayLevelModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('SaveDisplayLevel')->execute(
                    new SaveDisplayLevelRequestDTO($courseId, $displayLevel->getDisplayLevel())
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_course_course_edit',
                array('courseId' => $courseId)
            )
        );
    }
}
