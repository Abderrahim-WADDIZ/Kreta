<?php

namespace spec\Kreta\Component\Notification\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Kreta\Component\Notification\Model\Interfaces\NotificationInterface;
use Kreta\Component\Notification\NotifiableEvent\NotifiableEventInterface;
use Kreta\Component\Notification\NotifiableEvent\Registry\NotifiableEventRegistryInterface;
use Kreta\Component\Notification\Notifier\NotifierInterface;
use Kreta\Component\Notification\Notifier\Registry\NotifierRegistryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;

class DoctrineEventSubscriberSpec extends ObjectBehavior
{
    function let(NotifiableEventRegistryInterface $notifiableEventRegistry,
                 NotifierRegistryInterface $notifierRegistry )
    {
        $this->beConstructedWith($notifiableEventRegistry, $notifierRegistry);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\Notification\EventSubscriber\DoctrineEventSubscriber');
    }

    function it_returns_subscribed_events()
    {
        $subscribedEvents = array(
                                Events::preRemove,
                                Events::postRemove,
                                Events::prePersist,
                                Events::postPersist,
                                Events::preUpdate,
                                Events::postUpdate
                            );

        $this->getSubscribedEvents()->shouldReturn($subscribedEvents);
    }

    function it_handles_pre_remove(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification,
                                   EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('preRemove', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
                            $notifier, $notification);

        $args->getObject()->willReturn($object);
        $args->getEntityManager()->willReturn($manager);
        $this->preRemove($args);
    }

    function it_handles_post_remove(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification,
                                   EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('postRemove', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
                                $notifier, $notification);

        $args->getObject()->willReturn(new stdClass());
        $args->getEntityManager()->willReturn($manager);
        $this->postRemove($args);
    }

    function it_handles_pre_persist(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification,
                                   EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('prePersist', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
            $notifier, $notification);

        $args->getObject()->willReturn(new stdClass());
        $args->getEntityManager()->willReturn($manager);
        $this->prePersist($args);
    }

    function it_handles_post_persist(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification,
                                   EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('postPersist', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
            $notifier, $notification);

        $args->getObject()->willReturn(new stdClass());
        $args->getEntityManager()->willReturn($manager);
        $this->postPersist($args);
    }

    function it_handles_pre_update(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification, EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('preUpdate', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
            $notifier, $notification);

        $args->getObject()->willReturn(new stdClass());
        $args->getEntityManager()->willReturn($manager);
        $this->preUpdate($args);
    }

    function it_handles_post_update(NotifiableEventRegistryInterface $notifiableEventRegistry,
                                   NotifierRegistryInterface $notifierRegistry,
                                   NotifiableEventInterface $notifiableEvent,
                                   NotifierInterface $notifier,
                                   NotificationInterface $notification, EntityManager $manager,
                                   LifecycleEventArgs $args)
    {
        $object = new stdClass();
        $this->handleEventConfig('postUpdate', $object, $notifiableEventRegistry, $notifierRegistry, $notifiableEvent,
            $notifier, $notification);

        $args->getObject()->willReturn(new stdClass());
        $args->getEntityManager()->willReturn($manager);
        $this->postUpdate($args);
    }

    private function handleEventConfig($event, $object,
                                       NotifiableEventRegistryInterface $notifiableEventRegistry,
                                       NotifierRegistryInterface $notifierRegistry,
                                       NotifiableEventInterface $notifiableEvent,
                                       NotifierInterface $notifier,
                                       NotificationInterface $notification)
    {
        $notifiableEvent->supportsEvent($event, Argument::any())->shouldBeCalled()->willReturn(true);
        $notifiableEvent->getNotifications($event, $object)->shouldBeCalled()->willReturn(array($notification));
        $notifiableEventRegistry->getNotifiableEvents()->willReturn(array($notifiableEvent));
        $notifier->notify($notification)->shouldBeCalled();
        $notifierRegistry->getNotifiers()->shouldBeCalled()->willReturn(array($notifier));
    }
}
