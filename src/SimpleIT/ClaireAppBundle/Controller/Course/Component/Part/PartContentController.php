<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component\Part;

use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\GetPartContentResponse;
use OC\CLAIRE\BusinessRules\Responders\Course\PartContent\SavePartContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetDraftPartRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\DTO\SavePartContentRequestDTO;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartContentController extends AppController
{
    public function editViewAction($courseId, $partId)
    {
        /** @var GetPartContentResponse $ucResponse */
        $ucResponse =
            $this->get('oc.claire.use_cases.part_use_case_factory')
                ->make('GetDraftPartContent')
                ->execute(new GetDraftPartRequestDTO($courseId, $partId));

        return $this->render(
            'SimpleITClaireAppBundle:Course/Common/partial:editContent.html.twig',
            array(
                'content' => $ucResponse->getContent(),
                'action'  =>
                    $this->generateUrl(
                        'simple_it_claire_course_component_part_content_edit',
                        array('courseId' => $courseId, 'partId' => $partId)
                    )
            )
        );
    }

    public function editAction(Request $request, $courseId, $partId)
    {
        /** @var SavePartContentResponse $ucResponse */
        $ucResponse =
            $this->get('oc.claire.use_cases.part_use_case_factory')
                ->make('SavePartContent')
                ->execute(
                    new SavePartContentRequestDTO($courseId, $partId, $content = $request->get(
                        'content'
                    ))
                );

        return new Response($ucResponse->getContent());
    }
}
