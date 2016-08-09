<?php

namespace app\models\accounting;

use dee\db\QueryRecord;

/**
 * Description of InvoicePaid
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class InvoicePaid extends QueryRecord
{

    public function attributes()
    {
        return[
            'invoice_id', 'paid'
        ];
    }

    public static function query()
    {
        return PaymentDtl::find()
                ->alias('d')
                ->select(['invoice_id', 'paid' => 'sum([[d.value]])'])
                ->innerJoin(['p' => 'payment'], '[[p.id]]=[[d.payment_id]]')
                ->groupBy(['invoice_id']);
    }
}
