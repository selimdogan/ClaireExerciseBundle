<?php
namespace SimpleIT\ClaireAppBundle\Model;

use SimpleIT\ClaireAppBundle\Model\User\Author;

/**
 * Class AuthorFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorFactory
{
    /**
     * Create an author
     *
     * @param array $authorResource
     *
     * @return Author
     */
    public static function create(array $authorResource)
    {
        $author = new Author();
        if (isset($authorResource['id'])) {
            $author->setId($authorResource['id']);
        }
        if (isset($authorResource['username'])) {
            $author->setUsername($authorResource['username']);
        }
        if (isset($authorResource['slug'])) {
            $author->setSlug($authorResource['slug']);
        }
        if (isset($authorResource['firstname'])) {
            $author->setFirstname($authorResource['firstname']);
        }
        if (isset($authorResource['lastname'])) {
            $author->setLastname($authorResource['lastname']);
        }
        //FIXME metadatas
        return $author;
    }

    /**
     * Create a collection of authors
     *
     * @param array $authorResources The resources
     *
     * @return array The authors
     */
    public static function createCollection(array $authorResources)
    {
        $authors = array();
        foreach ($authorResources as $authorResource) {
            $author = self::create($authorResource);
            $authors[] = $author;
        }
        return $authors;
    }
}
