<?php

namespace AC\KalinkaBundle\EventDispatcher;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class KalinkaAuthorizationSubscriber implements EventSubscriberInterface
{
    private $auth;
    private $reader;

    // todo typehinting
    public function __construct($auth, $reader)
    {
        $this->auth = $auth;
        $this->reader = $reader;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerialize'
            ],
            [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize'
            ],
        ];
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $reflObject = new \ReflectionObject($event->getObject());
        $properties = ($reflObject->getProperties());
        $defaultGuardAnnotation = $this->reader->getClassAnnotation($reflObject, 'AC\KalinkaBundle\Annotation\DefaultGuard');
        if ($defaultGuardAnnotation) {
            $defaultGuard = $defaultGuardAnnotation->guard['value'];
        }

        foreach ($properties as $property) {
            $propAnnotation = $this->reader->getPropertyAnnotation($property, 'AC\KalinkaBundle\Annotation\Serialize');
            if ($propAnnotation) {
                $action = $propAnnotation->action['action'];
                $guard = $defaultGuard;
                // TODO: use property guard if one is set in the annotation
                $allowed = $this->auth->can($action, $guard);
            }
            if (!$allowed) {
                // TODO is this the best way to do this? Does it depend too much on model traits bundle?
                $propertyName = $property->name;
                $setMethod = "set" . ucfirst($propertyName);
                if (array_key_exists($setMethod, $object->getMethodMap())) {
                    $object->$setMethod(null);
                }

                // $object->$property = null;
            }
         }
            // $allowed = $this->auth->can(
            //     ''
            // );
            // print_r($allowed);
            // if auth says no
            // $this->auth->can('???')
                // set propery to null
            // print_r("Object:");
             # code...
            // print_r($event->getObject());
            // print_r("\n");
            // print_r("Visitor:");
            // print_r(get_class($event->getVisitor()));
            // print_r("\n");
            // print_r("Context:");
            // print_r(get_class($event->getContext()));
            // print_r("\n");
            // print_r("Type:");
            // print_r($event->getType());
            // print_r("\n");

    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $object = $event->getObject();
        //$event is JMS\Serializer\EventDispatcher\PreSerializeEvent
        // if (get_class($object) == "AC\FlagshipBundle\Document\User") {
        //     print_r("Object:");
        //     print_r($event->getObject());
        //     print_r("\n");
        //     print_r("Visitor:");
        //     print_r(get_class($event->getVisitor()));
        //     print_r("\n");
        //     print_r("Context:");
        //     print_r(get_class($event->getContext()));
        //     print_r("\n");
        //     print_r("Type:");
        //     print_r($event->getType());
        //     print_r("\n");
            // $object->setEmail(null);

        // }
    }
}
