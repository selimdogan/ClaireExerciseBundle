<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\MetadataResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\Utils\Collection\CollectionInformation;
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
     * Process edition of metadatas for a part
     *
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
                    $metadatas = $this->get('simple_it.claire.course.metadata')->saveFromPart(
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

//    /**
//     * Process edition of metadatas for a course
//     *
//     * @param Request $request  Request
//     * @param Form    $form     Form
//     * @param int     $courseId Course id
//     *
//     * @return \Symfony\Component\Form\Form
//     */
//    protected function processCourseEdit(
//        Request $request,
//        Form $form,
//        $courseId
//    )
//    {
//        if (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()) {
//            $form->bind($request);
//            if ($form->isValid()) {
//
//                /** @type MetadataResource $metadataResource */
//                $metadataResource = $form->getData();
//
//                $this->get('simple_it.claire.course.metadata')->saveFromCourse(
//                    $courseId,
//                    array($metadataResource->getKey() => $metadataResource->getValue())
//                );
//
//                return new AppResponse($metadataResource);
//            }
//        }
//
//        return $form;
//    }

    /**
     * Set status to draft if not defined in collection information
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return CollectionInformation
     */
    protected function setStatusToDraftIfNotDefined(CollectionInformation $collectionInformation)
    {
        $status = $collectionInformation->getFilter(CourseResource::STATUS);
        if (is_null($status)) {
            $collectionInformation->addFilter(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        }

        return $collectionInformation;
    }
}
