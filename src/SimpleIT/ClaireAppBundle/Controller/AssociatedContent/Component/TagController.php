<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\ViewModels\AssociatedContent\Tag\TagAssembler;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\Utils\FormatUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TagController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagController extends AppController
{

    /**
     * Get a list of tags
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAll(
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:list.html.twig',
            array('tags' => $tags)
        );
    }

    public function searchListAction(Request $request, CollectionInformation $collectionInformation)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAll(
            $collectionInformation
        );
        $formattedTags = array();
        /** @var TagResource $tag */
        foreach ($tags as $tag) {
            $formattedTags[$tag->getId()] = $tag->getName();
        }
        if ($request->isXmlHttpRequest()) {
            return new Response(
                $this->get('serializer')->serialize(
                    $formattedTags,
                    FormatUtils::JSON
                )
            );
        }
    }

    /**
     * View a tag
     *
     * @param int | string $tagIdentifier Tag id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @cache
     */
    public function viewAction($tagIdentifier, $categoryIdentifier)
    {
        /** @var TagResource $tag */
        $tag = $this->get('simple_it.claire.associated_content.tag')->get(
            $tagIdentifier
        );

        $category = $this->get('simple_it.claire.associated_content.category')->get($categoryIdentifier);

        $assembler = new TagAssembler();
        $vm = $assembler->writeTag($tag, $category);


        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:view.html.twig',
            array('vm' => $vm)
        );
    }

    /**
     * List recommended courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param mixed                 $tagIdentifier         Tag id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @cache
     */
    public function listRecommendedCoursesAction(
        CollectionInformation $collectionInformation,
        $tagIdentifier
    )
    {
        $courses = $this->get('simple_it.claire.associated_content.tag')->getRecommendedCourses(
            $tagIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:listRecommendedCourses.html.twig',
            array('courses' => $courses)
        );
    }

    /**
     * Get a list of courses of a category
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param mixed                 $tagIdentifier         Tag id | slug
     * @param string                $paginationUrl         Pagination url
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache
     */
    public function listCoursesAction(
        CollectionInformation $collectionInformation,
        $tagIdentifier,
        $paginationUrl
    )
    {
        $courses = $this->get('simple_it.claire.associated_content.tag')->getAllCourses(
            $tagIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:searchList.html.twig',
            array(
                'courses' => $courses,
                'collectionInformation' => $collectionInformation,
                'paginationUrl' => $paginationUrl
            )
        );
    }
}
