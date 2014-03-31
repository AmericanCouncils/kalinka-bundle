<?php

namespace AC\KalinkaBundle\Tests\Fixtures\FixtureBundle\Entity;

use AC\KalinkaBundle\Annotation as Kalinka;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Kalinka\DefaultGuard("robot")
 * @Serializer\ExclusionPolicy("all")
 */
class Robot
{
    /**
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $name;
    public function getName() {return $this->name;}
    public function setName($v) {$this->name = $v; return $this;}

    /**
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $make;
    public function getMake() {return $this->make;}
    public function setMake($v) {$this->make = $v; return $this;}

    /**
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $model;
    public function getModel() {return $this->model;}
    public function setModel($v) {$this->model = $v; return $this;}

    /**
     * @Serializer\Expose
     * @Serializer\Type("string")
     * @Kalinka\Deserialize("activate")
     */
    public $operationalStatus;
    public function getOperationalStatus() {return $this->operationalStatus;}
    public function setOperationalStatus($v) {$this->operationalStatus = $v; return $this;}

    /**
     * @Serializer\Expose
     * @Serializer\Type("boolean")
     * @Kalinka\Deserialize("befriend")
     */
    public $friendlyToHumans;
    public function getFriendlyToHumans() {return $this->friendlyToHumans;}
    public function setFriendlyToHumans($v) {$this->friendlyToHumans = $v; return $this;}

}
