<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\MetadataResource;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetadataController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByCourseController extends AbstractMetadataController
{
    /**
     * View a course duration
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTimeRequiredAction($courseIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourse(
            $courseIdentifier
        );
        $timeRequired = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_DURATION
        );
        $timeRequired = new \DateInterval($timeRequired);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewTimeRequired.html.twig',
            array(
                MetadataResource::COURSE_METADATA_TIME_REQUIRED => $timeRequired
            )
        );
    }

//    /**
//     * Edit a time required (GET)
//     *
//     * @param CollectionInformation $collectionInformation Collection information
//     * @param int                   $courseId              Course id
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function editTimeRequiredViewAction(
//        Request $request,
//        CollectionInformation $collectionInformation,
//        $courseId
//    )
//    {
//        $collectionInformation = $this->setStatusToDraftIfNotDefined($collectionInformation);
//
//        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourseByStatus(
//            $courseId,
//            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT),
//            $collectionInformation
//        );
//        $timeRequiredMetadata = new DifficultyMetadataResource(ArrayUtils::getValue(
//            $metadatas,
//            TimeRequiredMetadataResource::KEY
//        ));
//        $form = $this->createFormBuilder($timeRequiredMetadata)
//            ->add(
//                'value',
//                'choice',
//                array(
//                    'choices'     => TimeRequiredMetadataResource::getTimeRequired(),
//                    'empty_value' => '',
//                    'required'    => true
//                )
//            )
//            ->getForm();
//
//        return $this->render(
//            'SimpleITClaireAppBundle:Course/Metadata/Component:editTimeRequired.html.twig',
//            array(
//                'courseId' => $courseId,
//                'form'     => $form->createView(),
//                'action'   =>
//                    $this->generateUrl(
//                        'simple_it_claire_component_course_course_metadata_time_required_edit',
//                        array('courseId' => $courseId)
//                    )
//            )
//        );
//    }

    /**
     * View a course licence
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewLicenseAction($courseIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourse(
            $courseIdentifier
        );
        $license = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_LICENSE
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewLicense.html.twig',
            array(
                MetadataResource::COURSE_METADATA_LICENSE => $license
            )
        );
    }

    /**
     * View a course license
     *
     * @param Request      $request          Request
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLicenseByStatusAction(Request $request, $courseIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourseByStatus(
            $courseIdentifier,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT),
            new CollectionInformation
        );
        $license = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_LICENSE
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewLicense.html.twig',
            array(
                MetadataResource::COURSE_METADATA_LICENSE => $license
            )
        );
    }
}
