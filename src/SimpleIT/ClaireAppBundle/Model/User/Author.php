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

    /**
     * Getter for $id
     *
     * @return integer the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for $id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Getter for $username
     *
     * @return string the $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Setter for $username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Getter for $slug
     *
     * @return string the $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Setter for $slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Getter for $firstname
     *
     * @return string the $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Setter for $firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Getter for $lastname
     *
     * @return string the $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Setter for $lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Getter for $metadatas
     *
     * @return function the $metadatas
     */
    public function getMetadatas()
    {
        return $this->metadatas;
    }

    /**
     * Setter for $metadatas
     *
     * @param array $metadatas
     */
    public function setMetadatas(array $metadatas)
    {
        $this->metadatas = $metadatas;
    }

}
