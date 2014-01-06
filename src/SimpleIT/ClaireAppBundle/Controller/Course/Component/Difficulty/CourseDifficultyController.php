<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Difficulty;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\SaveCourseDifficultyRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\DifficultyModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\DifficultyType;
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
//            $difficulty = $this->get('oc.claire.use_cases.use_case_factory')
//                ->make('GetDraftCourseDifficulty')
//                ->execute(new GetDraftCourseDifficultyRequestDTO($courseId));
            $difficultyModel = new DifficultyModel();
            $difficultyModel->difficulty = 'facile';
            $form = $this->createForm(new DifficultyType(), $difficultyModel);

            return $this->render(
                'SimpleITClaireAppBundle:Course/Metadata/Component:editDifficulty.html.twig',
                array(
                    'form' => $form->createView(),
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_component_course_course_metadata_difficulty_edit',
                            array('courseId' => $courseId)
                        )
                )
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function editAction(Request $request, $courseId)
    {
        $difficultyModel = new DifficultyModel();
        $form = $this->createForm(new DifficultyType(), $difficultyModel);
        $form->bind($request);

        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.use_case_factory')
                ->make('SaveCourseDifficulty')
                ->execute(
                    new SaveCourseDifficultyRequestDTO($courseId, $difficultyModel->difficulty)
                );

            return new JsonResponse();
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }
    }
}
