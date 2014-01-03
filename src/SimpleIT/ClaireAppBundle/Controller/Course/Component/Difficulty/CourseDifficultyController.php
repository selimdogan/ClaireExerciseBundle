<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Difficulty;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\UseCases\Course\Difficulty\DTO\GetCourseDifficultyRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Type\Course\CourseDifficultyType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyController extends AppController
{
    public function editViewAction($courseId)
    {
        try {
            $difficulty = $this->get('oc.claire.use_cases.use_case_factory')
                ->make('GetCourseDifficulty')
                ->execute(new GetCourseDifficultyRequestDTO($courseId));
            $form = $this->createForm(new CourseDifficultyType(), $difficulty);

            return $this->render(
                'SimpleITClaireAppBundle:Course/Metadata/Component:editDifficulty.html.twig',
                array('form' => $form)
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }
}
