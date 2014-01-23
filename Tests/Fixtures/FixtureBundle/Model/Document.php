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
     */
    public $ownerId;

    /**
     * @Kalinka\Deserialize('administer')
     */
    public $comments;

    /**
     * @Kalinka\Deserialize('update')
     */
    public $language;

    /**
     * @Kalinka\Deserialize('update')
     */
    public $title;

    /**
     * @Kalinka\Deserialize('update')
     * @Serializer\Type("integer")
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

    public function locked() { return true; }
}
