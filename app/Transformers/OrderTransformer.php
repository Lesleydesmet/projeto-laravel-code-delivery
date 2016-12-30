<?php

namespace CodeDelivery\Transformers;

use League\Fractal\TransformerAbstract;
use CodeDelivery\Models\Order;

/**
 * Class OrderTransformer
 * @package namespace CodeDelivery\Transformers;
 */
class OrderTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['cupom'];
    protected $availableIncludes = [];

    /**
     * Transform the \Order entity
     * @param \Order $model
     *
     * @return array
     */
    public function transform(Order $model)
    {
        return [
            'id'    => (int) $model->id,
            'total' => (float) $model->total,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

    /* Método para serealizar o relacionamento */
    public function includeCupom(Order $model)
    {
        if(!$model->cupom){
            return null;
        };
        return $this->item($model->cupom, new CupomTransformer());
    }
}