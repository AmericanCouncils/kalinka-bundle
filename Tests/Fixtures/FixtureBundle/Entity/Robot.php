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
     *
     *
     */
    public $name;
    public function getName() {return $this->name;}
    public function setName($v) {$this->name = $v; return $this;}

    /**
     *
     *
     */
    public $make;
    public function getMake() {return $this->name;}
    public function setMake($v) {$this->name = $v; return $this;}

    /**
     *
     *
     */
    public $model;
    public function getModel() {return $this->name;}
    public function setModel($v) {$this->name = $v; return $this;}

    /**
     *
     *
     */
    public $operationalStatus;
    public function getOperationalStatus() {return $this->name;}
    public function setOperationalStatus($v) {$this->name = $v; return $this;}

    /**
     *
     *
     */
    public $friendlyToHumans;
    public function getFriendlyToHumans() {return $this->name;}
    public function setFriendlyToHumans($v) {$this->name = $v; return $this;}

}
