<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\PartDescription\GetDraftPartDescriptionResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO\GetDraftPartDescriptionRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\DTO\SavePartDescriptionRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\PartDescriptionModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\PartDescriptionType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartDescriptionController extends AppController
{
    public function editViewAction($courseId, $partId)
    {
        try {
            /** @var GetDraftPartDescriptionResponse $ucResponse */
            $ucResponse =
                $this->get('oc.claire.use_cases.part_use_case_factory')
                ->make('GetDraftPartDescription')
                ->execute(new GetDraftPartDescriptionRequestDTO($courseId, $partId));

            $form = $this->createForm(
                new PartDescriptionType(),
                new PartDescriptionModel($ucResponse->getDescription())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Common/partial:editDescription.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_component_part_description',
                            array('courseId' => $courseId, 'partId' => $partId)
                        ),
                    'form'      => $form->createView()
                )
            );

        } catch (CourseNotFoundException $cnfe) {
            throw new NotFoundHttpException();

        } catch (PartNotFoundException $pnfe) {
            throw new NotFoundHttpException();
        }
    }

    public function editAction(Request $request, $courseId, $partId)
    {
        $form = $this->createForm(
            new PartDescriptionType(),
            $description = new PartDescriptionModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.part_use_case_factory')
                ->make('SavePartDescription')->execute(
                    new SavePartDescriptionRequestDTO($courseId, $partId, $description->getDescription(
                    ))
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
