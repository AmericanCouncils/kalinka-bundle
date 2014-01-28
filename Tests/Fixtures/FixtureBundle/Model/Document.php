<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Model;

use AC\KalinkaBundle\Annotation as Kalinka;
use AC\ModelTraits\ArrayFactoryTrait;
use AC\ModelTraits\GetterSetterTrait;

/**
 * Kalinka\Guard('document')
 */
class Document
{
    use GetterSetterTrait, ArrayFactoryTrait;

    /**
     * @Kalinka\Serialize('administer')
     * @Serializer\Type("string")
     */
    public $ownerName;

    /**
     * @Kalinka\Deserialize('administer')
     * @Serializer\Type("string")
     */
    public $comments;

    /**
     * @Kalinka\Deserialize('update')
     * @Serializer\Type("string")
     */
    public $language;

    /**
     * @Kalinka\Deserialize('update')
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Kalinka\Deserialize('update')
     * @Serializer\Type("string")
     */
    public $content;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    public $dateCreated;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    public $dateModified;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Type("boolean")
     */
    protected $locked;
}
