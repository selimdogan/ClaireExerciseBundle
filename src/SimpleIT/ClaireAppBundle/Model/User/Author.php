<?php

namespace SimpleIT\ClaireAppBundle\Model\User;

/**
 * Description of Author
 *
 * @author Isabelle Bruchet <isabelle.bruchet@simple-it.fr>
 */
class Author
{
    /**
     * @var integer $id Id of author
     */
    private $id;

    /**
     * @var string $username Username
     */
    private $username;

    /**
     * @var string $slug Slug
     */
    private $slug;

    /**
     * @var string $firstname Firstname
     */
    private $firstname;

    /**
     * @var string $lastname Lastname
     */
    private $lastname;

    /**
     * @var array $metadatas Metadatas
     */
    private $metadatas = array();

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function setMetadatas($metadatas)
    {
        $this->metadatas = $metadatas;
    }
}