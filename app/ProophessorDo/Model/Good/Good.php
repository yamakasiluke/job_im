<?php
declare(strict_types=1);

namespace Prooph\ProophessorDo\Model\Good;


use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\Good\Event\PublishGoodEvent
;

class Good extends AggregateRoot implements Entity
{

    /**
     * @var GoodId
     */
    private $goodId;

    public static function publish(GoodId $goodId): Good
    {
        $self = new self();

        $self->recordThat(PublishGoodEvent::publish($goodId));

        return $self;
    }
    public function sameIdentityAs(Entity $other): bool
    {
        return get_class($this) === get_class($other) && $this->goodId->sameValueAs($other->goodId);
    }



    protected function whenPublishGoodEvent(PublishGoodEvent $event): void
    {
        $this->goodId = $event->goodId();
    }

    protected function aggregateId(): string
    {
        return $this->goodId->toString();
    }


    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);

        if (! method_exists($this, $handler)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }

        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'when' . implode(array_slice(explode('\\', get_class($e)), -1));
    }
}
