<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Course;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\CourseDifficulty\GetDraftCourseDifficultyResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\DTO\GetDraftCourseDifficultyRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\CourseDifficulty\DTO\SaveCourseDifficultyRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\CourseDifficultyModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\CourseDifficultyType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyController extends AppController
{
    public function editViewAction($courseId)
    {
        try {
            /** @var GetDraftCourseDifficultyResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('GetDraftCourseDifficulty')
                ->execute(new GetDraftCourseDifficultyRequestDTO($courseId));

            $form = $this->createForm(
                new CourseDifficultyType(),
                new CourseDifficultyModel($ucResponse->getDifficulty())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Metadata/Component:editDifficulty.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_component_course_difficulty_edit',
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
            new CourseDifficultyType(),
            $difficulty = new CourseDifficultyModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.course_use_case_factory')
                ->make('SaveCourseDifficulty')->execute(
                    new SaveCourseDifficultyRequestDTO($courseId, $difficulty->getDifficulty())
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
