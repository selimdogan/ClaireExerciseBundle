<?php


namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
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
     * @param string           $metadataName     Metadata name
     *
     * @return \Symfony\Component\Form\Form
     */
    protected function processEdit(
        Request $request,
        Form $form,
        $courseIdentifier,
        $partIdentifier,
        $metadataName
    )
    {

        if (RequestUtils::METHOD_POST == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                if (isset($metadatas[$metadataName])) {
                    $metadata = $metadatas[$metadataName];
                } else {
                    $metadata = null;
                }

                $metadatas = $form->getData();
                if ($metadata != $metadatas[$metadataName]) {
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
