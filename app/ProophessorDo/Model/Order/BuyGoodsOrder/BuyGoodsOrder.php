<?php


namespace App\ProophessorDo\Model\Order\BuyGoodsOrder;


use Prooph\EventSourcing\AggregateChanged;

class BuyGoodsOrder extends \Prooph\EventSourcing\AggregateRoot
{
    private $WantBuyGoodsId;

    private $EstabalishTime;

    private $CloseTime;

    private $CancelTime;

    private $DelieverTime;

    private $GoodsId;

    private $address;

    private $price;

    private $status;

    private $comment;

    private $gps;

    private $ip;

    private $CommistionFee;

    private $ConfirmTime;

    private $DoneTime;


    public function MakeOrder():void{}
    protected function WhenMakeOrder(): void {}

    public function PayOrder():void{}
    protected function WhenPayOrder(): void {}

    public function DelieveringOrder():void{}
    protected function WhenDelieveringOrder(): void {}

    public function DelieveredOrder():void{}
    protected function WhenDelieveredOrder(): void {}

    public function DoneOrder():void{}
    protected function WhenDoneOrder(): void {}






    protected function aggregateId(): string
    {
        // TODO: Implement aggregateId() method.
    }

    /**
     * @inheritDoc
     */
    protected function apply(AggregateChanged $event): void
    {
        // TODO: Implement apply() method.
    }
}
