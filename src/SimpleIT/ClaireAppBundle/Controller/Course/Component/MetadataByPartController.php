<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetadataController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataByPartController extends AppController
{
    /**
     * Edit a part description
     *
     * @param Request          $request          Request
     * @param integer | string $courseIdentifier Course id | slug
     * @param integer | string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRatingAction(Request $request, $courseIdentifier, $partIdentifier)
    {
//        $metadatas = $this->get('simpleit.claire.course.metadata')->get(
//            $courseIdentifier,
//            $partIdentifier
//        );
        //FIXME
        $metadatas['aggregate-rating'] = 3.7;

        return $this->render(
            'SimpleITClaireAppBundle:Course/MetadataByPart/Component:viewRating.html.twig',
            array('rate' => $metadatas['aggregate-rating']
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
        // TODO
        $metadata = array();

        $form = $this->createFormBuilder($metadata)
            ->add('key')
            ->getForm();

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
        // TODO
        $metadata = array();

        $form = $this->createFormBuilder($metadata)
            ->add('key')
            ->getForm();

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
        // TODO
        $metadata = array();

        $form = $this->createFormBuilder($metadata)
            ->add('key')
            ->getForm();

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
        // TODO
        $metadata = array();

        $form = $this->createFormBuilder($metadata)
            ->add('key')
            ->getForm();

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
