<?php

namespace Botble\Eurobank\Services\Gateways;

use Botble\Eurobank\Services\Abstracts\EurobankPaymentAbstract;
use Exception;
use Illuminate\Http\Request;

class EurobankPaymentService extends EurobankPaymentAbstract
{
    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
    }
}
