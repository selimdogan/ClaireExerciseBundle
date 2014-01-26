<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Part;

use OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException;
use OC\CLAIRE\BusinessRules\Exceptions\Course\Part\PartNotFoundException;
use OC\CLAIRE\BusinessRules\Responders\Course\Part\GetPartResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetDraftPartRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\SavePartRequestBuilder;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Course\Model\PartTitleModel;
use SimpleIT\ClaireAppBundle\Form\Course\Type\PartTitleType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartTitleController extends AppController
{
    public function editViewAction($courseId, $partId)
    {
        try {
            /** @var GetPartResponse $ucResponse */
            $ucResponse =
                $this->get('oc.claire.use_cases.part_use_case_factory')
                    ->make('GetDraftPart')
                    ->execute(new GetDraftPartRequestDTO($courseId, $partId));

            $form = $this->createForm(
                new PartTitleType(),
                new PartTitleModel($ucResponse->getTitle())
            );

            return $this->render(
                'SimpleITClaireAppBundle:Course/Common/partial:editTitle.html.twig',
                array(
                    'actionUrl' => $this->generateUrl(
                            'simple_it_claire_course_component_part_title_edit',
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
            new PartTitleType(),
            $model = new PartTitleModel()
        );
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('oc.claire.use_cases.part_use_case_factory')
                ->make('SavePart')->execute(
                    SavePartRequestBuilder::create()
                        ->part($partId)
                        ->fromCourse($courseId)
                        ->withTitle($model->getTitle())
                        ->build()
                );
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        return new JsonResponse();
    }
}
