<?php

namespace UserBundle\Entity;

use CourseBundle\Entity\GradeClass;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use UserBundle\Repository\StudentRepository;

/**
 * Student
 *
 * @ORM\Entity(repositoryClass="UserBundle\Repository\StudentRepository")
 */
class Student implements AdvancedUserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var ProfilePicture
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\ProfilePicture", cascade={"persist"})
     * @ORM\JoinColumn(name="profile_picture_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profilePicture;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @var string
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="_", unique=true, unique_base="username")
     * @ORM\Column(type="string", length=101, unique=true)
     */
    private $username;

    /**
     * @var GradeClass
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\GradeClass")
     * @ORM\JoinColumn(name="grade_class_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $gradeClass;

    /**
     * @var string
     * @ORM\Column(name="barcode", type="string", length=20, unique=true)
     */
    private $barcode;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\StudentParent", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parents;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * Student constructor.
     */
    public function __construct()
    {
        $this->parents = new ArrayCollection();
        $this->roles = ['ROLE_STUDENT'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Student
     */
    public function setId(int $id): Student
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ProfilePicture|null
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param ProfilePicture|null $profilePicture
     *
     * @return User
     */
    public function setProfilePicture(ProfilePicture $profilePicture = null) : User
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return Student
     */
    public function setUsername(string $username): Student
    {
        $this->username = $username;
        return $this;
    }


    /**
     * @return GradeClass
     */
    public function getGradeClass(): GradeClass
    {
        return $this->gradeClass;
    }

    /**
     * @param GradeClass $gradeClass
     *
     * @return Student
     */
    public function setGradeClass(GradeClass $gradeClass): Student
    {
        $this->gradeClass = $gradeClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     *
     * @return Student
     */
    public function setBarcode(string $barcode): Student
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParents(): ArrayCollection
    {
        return $this->parents;
    }

    /**
     * @param StudentParent $parent
     *
     * @return Student
     */
    public function addParent(StudentParent $parent): Student
    {
        $this->parents->add($parent);
        return $this;
    }


    public function removeParent(StudentParent $parent): Student
    {
        $this->parents->remove($parent);
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Student
     */
    public function setFirstName(string $firstName): Student
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Student
     */
    public function setLastName(string $lastName): Student
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Student
     */
    public function setEmail(string $email): Student
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     *
     * @return Student
     */
    public function setActive(bool $isActive): Student
    {
        $this->isActive = $isActive;
        return $this;
    }


    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->barcode,
            $this->roles,
            $this->isActive
        ]);
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->barcode,
            $this->roles,
            $this->isActive
            ) = unserialize($serialized);
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return null;
    }
}
