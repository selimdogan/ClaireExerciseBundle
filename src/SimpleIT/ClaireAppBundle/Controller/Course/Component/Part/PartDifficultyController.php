<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\PartDifficulty\GetDraftPartDifficultyResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO\GetDraftPartDifficultyRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\DTO\SavePartDifficultyRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\PartDifficultyModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\PartDifficultyType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartDifficultyController extends AppController
{
    public function editViewAction($courseId, $partId)
    {
        try {
            /** @var GetDraftPartDifficultyResponse $ucResponse */
            $ucResponse = $this->get('oc.claire.use_cases.use_case_factory')
                ->make('GetDraftPartDifficulty')
                ->execute(new GetDraftPartDifficultyRequestDTO($courseId, $partId));

            $form = $this->createForm(
                new PartDifficultyType(),
                new PartDifficultyModel($ucResponse->getDifficulty())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Metadata/Component:editDifficulty.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_component_course_course_metadata_difficulty_edit',
                            array('courseId' => $courseId, 'partId' => $partId)
                        ),
                    'form'      => $form->createView()
                )
            );

        } catch (PartNotFoundException $cnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function editAction(Request $request, $courseId, $partId)
    {
        $form = $this->createForm(
            new PartDifficultyType(),
            $difficulty = new PartDifficultyModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.use_case_factory')
                ->make('SavePartDifficulty')->execute(
                    new SavePartDifficultyRequestDTO($courseId, $partId, $difficulty->getDifficulty(
                    ))
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
