<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    /**
     * Available properties
     *
     * FOS properties
     *
     * protected $id;
     * protected $username;
     * protected $usernameCanonical;
     * protected $email;
     * protected $emailCanonical;
     * protected $enabled;
     * protected $salt;
     * protected $password;
     * protected $plainPassword;
     * protected $lastLogin;
     * protected $confirmationToken;
     * protected $passwordRequestedAt;
     * protected $groups;
     * protected $locked;
     * protected $expired;
     * protected $expiresAt;
     * protected $roles;
     * protected $credentialsExpired;
     * protected $credentialsExpireAt;
     *
     * Sonata properties
     *
     * protected $createdAt;
     * protected $updatedAt;
     * protected $twoStepVerificationCode;
     * protected $dateOfBirth;
     * protected $firstname;
     * protected $lastname;
     * protected $website;
     * protected $biography;
     * protected $gender; // set the default to unknown
     * protected $locale;
     * protected $timezone;
     * protected $phone;
     * protected $facebookUid;
     * protected $facebookName;
     * protected $facebookData;
     * protected $twitterUid;
     * protected $twitterName;
     * protected $twitterData;
     * protected $gplusUid;
     * protected $gplusName;
     * protected $gplusData;
     * protected $token;
     */

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group")
     */
    protected $groups;

    /**
     * @var string
     * @ORM\Column(name="youtube_uid", type="string", nullable=true)
     */
    protected $youtubeUid;

    /**
     * @var string
     * @ORM\Column(name="youtube_name", type="string", nullable=true)
     */
    protected $youtubeName;

    /**
     * @var array
     * @ORM\Column(name="youtube_data", type="string", nullable=true)
     */
    protected $youtubeData;

    /**
     * @var string
     * @ORM\Column(name="linkedin_uid", type="string", nullable=true)
     */
    protected $linkedinUid;

    /**
     * @var string
     * @ORM\Column(name="linkedin_name", type="string", nullable=true)
     */
    protected $linkedinName;

    /**
     * @var array
     * @ORM\Column(name="linkedin_data", type="string", nullable=true)
     */
    protected $linkedinData;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set youtubeUid
     *
     * @param string $youtubeUid
     * @return User
     */
    public function setYoutubeUid($youtubeUid)
    {
        $this->youtubeUid = $youtubeUid;

        return $this;
    }

    /**
     * Get youtubeUid
     *
     * @return string
     */
    public function getYoutubeUid()
    {
        return $this->youtubeUid;
    }

    /**
     * Set youtubeName
     *
     * @param string $youtubeName
     * @return User
     */
    public function setYoutubeName($youtubeName)
    {
        $this->youtubeName = $youtubeName;

        return $this;
    }

    /**
     * Get youtubeName
     *
     * @return string
     */
    public function getYoutubeName()
    {
        return $this->youtubeName;
    }


    /**
     * Set youtubeData
     *
     * @param string $youtubeData
     * @return User
     */
    public function setYoutubeData($youtubeData)
    {
        $this->youtubeData = $youtubeData;

        return $this;
    }

    /**
     * Get youtubeData
     *
     * @return string
     */
    public function getYoutubeData()
    {
        return $this->youtubeData;
    }

    /**
     * Set linkedinUid
     *
     * @param string $linkedinUid
     * @return User
     */
    public function setLinkedinUid($linkedinUid)
    {
        $this->linkedinUid = $linkedinUid;

        return $this;
    }

    /**
     * Get linkedinUid
     *
     * @return string
     */
    public function getLinkedinUid()
    {
        return $this->linkedinUid;
    }

    /**
     * Set linkedinName
     *
     * @param string $linkedinName
     * @return User
     */
    public function setLinkedinName($linkedinName)
    {
        $this->linkedinName = $linkedinName;

        return $this;
    }

    /**
     * Get linkedinName
     *
     * @return string
     */
    public function getLinkedinName()
    {
        return $this->linkedinName;
    }

    /**
     * Set linkedinData
     *
     * @param string $linkedinData
     * @return User
     */
    public function setLinkedinData($linkedinData)
    {
        $this->linkedinData = $linkedinData;

        return $this;
    }

    /**
     * Get linkedinData
     *
     * @return string
     */
    public function getLinkedinData()
    {
        return $this->linkedinData;
    }
}
