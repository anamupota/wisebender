<?php
// src/Ace/ProjectBundle/Entity/Project.php

namespace Ace\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ace\UserBundle\Entity\User;

/**
 * @ORM\Entity
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
	 * @ORM\Column(type="string", length="255")
	 */
    private $name;

    /**
	 * @ORM\Column(type="string", length="255")
	 */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_public;

    /**
	 * @ORM\Column(type="string", length="255")
     */
    private $type;

    /**
	 * @ORM\Column(type="string", length="255")
	 */
    private $projectfiles_id;

    /**
     * @ORM\ManyToOne(targetEntity="Ace\UserBundle\Entity\User")
     **/
	protected $owner;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 **/
	protected $parent;


    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $git_url;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $git_commit_sha;


    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_wiselib_clone;

	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set is_public
     *
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->is_public = $isPublic;
    }

    /**
     * Get is_public
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->is_public;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set projectfiles_id
     *
     * @param string $projectfilesId
     */
    public function setProjectfilesId($projectfilesId)
    {
        $this->projectfiles_id = $projectfilesId;
    }

    /**
     * Get projectfiles_id
     *
     * @return string 
     */
    public function getProjectfilesId()
    {
        return $this->projectfiles_id;
    }

    /**
     * Set owner
     *
     * @param Ace\UserBundle\Entity\User $owner
     */
    public function setOwner(\Ace\UserBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return Ace\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get git url for the project
     *
     * @return string
     */
    public function getGitUrl()
    {
        return $this->git_url;
    }

    /**
     * Set git url for the project
     *
     * @param string
     */
    public function setGitUrl($url)
    {
        $this->git_url = $url;
    }

    /**
     * Get latest git commit SHA for the project
     *
     * @return string
     */
    public function getGitCommitSHA()
    {
        return $this->git_commit_sha;
    }

    /**
     * Set latest git commit SHA for the project
     *
     * @param boolean
     */
    public function setIsWiselibClone($val)
    {
        $this->is_wiselib_clone = $val;
    }

    /**
     * Get latest git commit SHA for the project
     *
     * @return boolean
     */
    public function getIsWiselibClone()
    {
        return $this->is_wiselib_clone;
    }

    /**
     * Set latest git commit SHA for the project
     *
     * @param string
     */
    public function setGitCommitSHA($sha)
    {
        $this->git_commit_sha = $sha;
    }
}