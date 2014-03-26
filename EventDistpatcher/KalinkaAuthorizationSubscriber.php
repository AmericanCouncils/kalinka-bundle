<?php

namespace AC\KalinkaBundle\EventDispatcher;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class KalinkaAuthorizationSubscriber implements EventSubscriberInterface
{
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
        //$event is JMS\Serializer\EventDispatcher\PreSerializeEvent
        // if (get_class($object) == "AC\FlagshipBundle\Document\User") {
            // print_r("Object:");
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
            // $object->setEmail(null);

        // }
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

