<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity;

use AC\KalinkaBundle\Annotation as Kalinka;
use AC\ModelTraits\ArrayFactoryTrait;
use AC\ModelTraits\GetterSetterTrait;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Kalinka\DefaultGuard("document")
 */
class Document
{
    use GetterSetterTrait, ArrayFactoryTrait;

    /**
     * @Serializer\Type("string")
     * @Kalinka\Serialize(action="administer")
     */
    public $ownerName;
    public function setOwnerName($v) {$this->ownerName = $v; return $this;}
    public function getOwnerName() {return $this->ownerName;}

    /**
     * @Kalinka\Deserialize(guard="alternative_document", action="something")
     * @Serializer\Type("string")
     */
    public $comments;
    public function setComments($v) {$this->comments = $v; return $this;}
    public function getComments() {return $this->comments;}

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     */
    public $language;
    public function setLanguage($v) {$this->language = $v; return $this;}
    public function getLanguage() {return $this->language;}

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     */
    public $title;
    public function setTitle($v) {$this->title = $v; return $this;}
    public function getTitle() {return $this->title;}

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     */
    public $content;
    public function setContent($v) {$this->content = $v; return $this;}
    public function getContent() {return $this->content;}

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    public $dateCreated;
    public function setDateCreated($v) {$this->dateCreated = $v; return $this;}
    public function getDateCreated() {return $this->dateCreated;}

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    public $dateModified;
    public function setDateModified($v) {$this->dateModified = $v; return $this;}
    public function getDateModified() {return $this->dateModified;}

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("boolean")
     */
    protected $locked;
}
