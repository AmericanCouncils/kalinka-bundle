<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity;

use AC\KalinkaBundle\Annotation as Kalinka;
use AC\ModelTraits\ArrayFactoryTrait;
use AC\ModelTraits\GetterSetterTrait;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Kalinka\DefaultGuard("document")
 * @Serializer\ExclusionPolicy("all")
 */
class Document
{
    use GetterSetterTrait;
    use ArrayFactoryTrait;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Kalinka\Serialize(action="administer")
     */
    public $ownerName;

    /**
     * @Kalinka\Deserialize(guard="alternative_document", action="something")
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    public $comments;

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    public $language;

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    public $title;

    /**
     * @Kalinka\Deserialize("update")
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    public $content;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     * @Serializer\Expose
     */
    public $dateCreated;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     * @Serializer\Expose
     */
    public $dateModified;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     */
    protected $locked;
}
