<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\MetadataResource;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\Utils\DateUtils;
use Symfony\Component\HttpFoundation\Request;

use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class MetadataController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByPartController extends AbstractMetadataController
{
    /**
     * View a collection of informations
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewInformationsAction($courseIdentifier, $partIdentifier)
    {
        $informations = $this->get('simple_it.claire.course.metadata')->getInformationsFromPart(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewInformations.html.twig',
            array('informations' => $informations)
        );
    }

    /**
     * Show part rate
     *
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewRatingAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        $rate = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_AGGREGATE_RATING
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:viewRating.html.twig',
            array(
                MetadataResource::COURSE_METADATA_AGGREGATE_RATING => $rate
            )
        );
    }

    /**
     * View a course description
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewDescriptionAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        $description = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_DESCRIPTION
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewDescription.html.twig',
            array(
                MetadataResource::COURSE_METADATA_DESCRIPTION => $description
            )
        );
    }

    /**
     * Edit a part description
     *
     * @param Request          $request          Request
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDescriptionAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $metadataName = MetadataResource::COURSE_METADATA_DESCRIPTION;
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );

        $form = $this->createFormBuilder($metadatas)
            ->add($metadataName, 'textarea')
            ->getForm();

        $form = $this->processPartEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
            $metadatas,
            $metadataName
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:editDescription.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'form'             => $form->createView()
            )
        );
    }

    /**
     * View a part image
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewImageAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        $image = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_IMAGE
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewImage.html.twig',
            array(
                MetadataResource::COURSE_METADATA_IMAGE => $image
            )
        );
    }

    /**
     * Edit a part image
     *
     * @param Request          $request          Request
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editImageAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $metadataName = MetadataResource::COURSE_METADATA_IMAGE;
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );

        $form = $this->createFormBuilder($metadatas)
            ->add($metadataName, 'url')
            ->getForm();

        $form = $this->processPartEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
            $metadatas,
            $metadataName
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:editImage.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'form'             => $form->createView()
            )
        );
    }

    /**
     * View a course difficulty
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewDifficultyAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        $difficulty = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_DIFFICULTY
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewDifficulty.html.twig',
            array(
                MetadataResource::COURSE_METADATA_DIFFICULTY => $difficulty
            )
        );
    }

    /**
     * Edit a part difficulty
     *
     * @param Request          $request          Request
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDifficultyAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $metadataName = MetadataResource::COURSE_METADATA_DIFFICULTY;
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        //FIXME trans
        $form = $this->createFormBuilder($metadatas)
            ->add(
                $metadataName,
                'choice',
                array(
                    'choices'     => array(
                        'easy'   => 'facile',
                        'middle' => 'moyen',
                        'hard'   => 'difficile'
                    ),
                    'empty_value' => '',
                    'required'    => true
                )
            )
            ->getForm();

        $form = $this->processPartEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
            $metadatas,
            $metadataName
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:editDifficulty.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'form'             => $form->createView()
            )
        );
    }

    /**
     * Edit a part duration
     *
     * @param Request          $request          Request
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDurationAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $metadataName = MetadataResource::COURSE_METADATA_TIME_REQUIRED;
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );

        if (isset($metadatas[$metadataName])) {
            $currentDuration = new \DateInterval($metadatas[$metadataName]);
            $durationUnits = array(
                'days'    => $currentDuration->d,
                'hours'   => $currentDuration->h,
                'minutes' => $currentDuration->i
            );
        } else {
            $currentDuration = new \DateInterval('P0D');
            $durationUnits = array();
        }

        $form = $this->createFormBuilder($durationUnits)
            ->add('days', null, array('required' => false))
            ->add('hours', null, array('required' => false))
            ->add('minutes', null, array('required' => false))
            ->getForm();

        if (RequestUtils::METHOD_POST == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $durationUnits = $form->getData();
                $duration = new \DateInterval('P0D');
                $duration->d = $durationUnits['days'];
                $duration->m = $durationUnits['hours'];
                $duration->i = $durationUnits['minutes'];

                if ($duration != $currentDuration) {
                    $this->get('simple_it.claire.course.metadata')->saveFromPart(
                        $courseIdentifier,
                        $partIdentifier,
                        array($metadataName => DateUtils::DateIntervalToString($duration))
                    );
                }
            }
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:editDuration.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'form'             => $form->createView()
            )
        );
    }

    /**
     * View a course duration
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function viewTimeRequiredAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromPart(
            $courseIdentifier,
            $partIdentifier
        );
        $timeRequired = ArrayUtils::getValue(
            $metadatas,
            MetadataResource::COURSE_METADATA_DURATION
        );
        if (!is_null($timeRequired)) {
            $timeRequired = new \DateInterval($timeRequired);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Metadata/Component:viewTimeRequired.html.twig',
            array(
                MetadataResource::COURSE_METADATA_TIME_REQUIRED => $timeRequired
            )
        );
    }
}
