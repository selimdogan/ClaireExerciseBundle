<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\DateUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetadataController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByPartController extends AbstractMetadataController
{
    /**
     * Show part rate
     *
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRatingAction($courseIdentifier, $partIdentifier)
    {
        $metadatas = $this->get('simple_it.claire.course.metadata')->get(
            $courseIdentifier,
            $partIdentifier
        );
        $rate = null;
        if (isset($metadatas['aggregate-rating'])) {
            $rate = $metadatas['aggregate-rating'];
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:viewRating.html.twig',
            array(
                'rate' => $rate
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
        $metadataName = 'description';
        $metadatas = $this->get('simple_it.claire.course.metadata')->get(
            $courseIdentifier,
            $partIdentifier
        );

        $form = $this->createFormBuilder($metadatas)
            ->add($metadataName)
            ->getForm();

        $form = $this->processEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
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
        $metadataName = 'image';
        $metadatas = $this->get('simple_it.claire.course.metadata')->get(
            $courseIdentifier,
            $partIdentifier
        );

        $form = $this->createFormBuilder($metadatas)
            ->add($metadataName, 'url')
            ->getForm();

        $form = $this->processEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
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
        $metadataName = 'difficulty';
        $metadatas = $this->get('simple_it.claire.course.metadata')->get(
            $courseIdentifier,
            $partIdentifier
        );

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

        $form = $this->processEdit(
            $request,
            $form,
            $courseIdentifier,
            $partIdentifier,
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
        $metadataName = 'duration';
        $metadatas = $this->get('simple_it.claire.course.metadata')->get(
            $courseIdentifier,
            $partIdentifier
        );

        if (isset($metadatas[$metadataName])) {
            $currentDuration = new \DateInterval($metadatas[$metadataName]);
            $durationUnits = array('days'    => $currentDuration->d,
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
                    $this->get('simple_it.claire.course.metadata')->save(
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
}
