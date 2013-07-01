<?php


namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
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
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     * @param string           $metadataName     Metadata name
     *
     * @return \Symfony\Component\Form\Form
     */
    protected function processEdit(
        Request $request,
        $form,
        $metadataName
    )
    {

        if (RequestUtils::METHOD_POST == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                if (isset($metadatas[$metadataName])) {
                    $description = $metadatas[$metadataName];
                } else {
                    $description = null;
                }

                $metadatas = $form->getData();
                if ($description != $metadatas[$metadataName]) {
                    $metadatas = $this->get('simple_it.claire.course.metadata')->save(
                        $courseIdentifier,
                        $partIdentifier,
                        array($metadataName => $metadatas[$metadataName])
                    );
                }
            }
        }

        return $form;
    }
}
