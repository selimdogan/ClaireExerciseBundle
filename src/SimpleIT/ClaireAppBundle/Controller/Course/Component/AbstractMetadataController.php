<?php


namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\ArrayUtils;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractMetadataController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
abstract class AbstractMetadataController extends AppController
{
    /**
     * @param Request          $request          Request
     * @param Form             $form             Form
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     * @param array            $metadatas        Metadatas
     * @param string           $metadataName     Metadata name
     *
     * @return \Symfony\Component\Form\Form
     */
    protected function processPartEdit(
        Request $request,
        Form $form,
        $courseIdentifier,
        $partIdentifier,
        $metadatas,
        $metadataName
    )
    {

        if (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()) {
            $form->bind($request);
            if ($form->isValid()) {
                $actualMetadata = ArrayUtils::getValue($metadatas, $metadataName);

                $metadatas = $form->getData();
                $metadata = ArrayUtils::getValue($metadatas, $metadataName);
                if ($actualMetadata != $metadata) {
                    $metadatas = $this->get('simple_it.claire.course.metadata')->saveByPart(
                        $courseIdentifier,
                        $partIdentifier,
                        array($metadataName => $metadata)
                    );

                    return new AppResponse(array($metadataName => $metadatas[$metadataName]));
                }
            }
        }

        return $form;
    }
}
