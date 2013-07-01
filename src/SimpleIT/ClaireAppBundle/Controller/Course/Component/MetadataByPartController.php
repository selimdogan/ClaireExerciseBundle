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
        $metadatas = array();
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $metadatas = $this->get('simple_it.claire.course.metadata')->get(
                $courseIdentifier,
                $partIdentifier
            );
        }

        $form = $this->createFormBuilder($metadatas)
            ->add('image')
            ->getForm();

        if (RequestUtils::METHOD_POST == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $metadatas = $form->getData();
                $metadatas = $this->get('simple_it.claire.course.metadata')->save(
                    $courseIdentifier,
                    $partIdentifier,
                    array('image' => $metadatas['image'])
                );
            }
        }

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
