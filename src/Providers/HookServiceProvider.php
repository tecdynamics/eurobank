<?php

namespace Botble\Eurobank\Providers;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Eurobank\Services\Models\EurobankModel;
use Botble\Eurobank\Services\Gateways\EurobankPaymentService;
use Exception;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Throwable;
use Cart;
use OrderHelper;


class HookServiceProvider extends ServiceProvider
{


    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerEurobankMethod'], 17, 2);
//        $this->app->booted(function () {
//            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithEurobank'], 17, 2);
//        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 99);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['EUROBANK'] = EUROBANK_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == EUROBANK_PAYMENT_METHOD_NAME) {
                $value = 'Eurobank';
            }

            return $value;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == EUROBANK_PAYMENT_METHOD_NAME) {
                $value = Html::tag('span', PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label'])
                    ->toHtml();
            }

            return $value;
        }, 23, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == EUROBANK_PAYMENT_METHOD_NAME) {
                $data = EurobankPaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == EUROBANK_PAYMENT_METHOD_NAME) {
                $paymentService = new EurobankPaymentService;
                $paymentDetail = $paymentService->getPaymentDetails($payment->charge_id);

                if ($paymentDetail) {
                    $data = view('plugins/eurobank::detail', ['payment' => $paymentDetail, 'paymentModel' => $payment])->render();
                }
            }

            return $data;
        }, 20, 2);

//        add_filter(PAYMENT_FILTER_GET_REFUND_DETAIL, function ($data, $payment, $refundId) {
//            if ($payment->payment_channel == EUROBANK_PAYMENT_METHOD_NAME) {
//                $refundDetail = (new EurobankPaymentService)->getRefundDetails($refundId);
//                if (!Arr::get($refundDetail, 'error')) {
//                    $refunds = Arr::get($payment->metadata, 'refunds', []);
//                    $refund = collect($refunds)->firstWhere('id', $refundId);
//                    $refund = array_merge((array) $refund, Arr::get($refundDetail, 'data', []));
//                    return array_merge($refundDetail, [
//                        'view' => view('plugins/eurobank::refund-detail', ['refund' => $refund, 'paymentModel' => $payment])->render(),
//                    ]);
//                }
//                return $refundDetail;
//            }
//
//            return $data;
//        }, 20, 3);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/eurobank::settings')->render();
    }


    /**
     * @param string $html
     * @param array $data
     * @return string
     */
    public function registerEurobankMethod($html, $data)
            {
                $order = OrderHelper::getOrderSessionData();
                $marketplace= $order['marketplace']??false;
                if($marketplace) {
                    $marketplace = reset($marketplace);
                }

        $eurobank_key = get_payment_setting('client_id', EUROBANK_PAYMENT_METHOD_NAME);
        $eurobank_secret = get_payment_setting('secret', EUROBANK_PAYMENT_METHOD_NAME);

        if (!$eurobank_key || !$eurobank_secret) {
            return $html;
        }
       $data['errorMessage'] = null;
                if (!$marketplace) {
                    $data['orderId'] = Arr::get($order, 'order_id', 0);
                }else{
                    $data['orderId'] = Arr::get($marketplace, 'created_order_id', 0);
                }
        $data['paymentId'] = Str::random(20);
        return $html . view('plugins/eurobank::paymentpage', $data)->render();
    }


    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function checkoutWithEurobank(array $data, Request $request)
    {
        $order = OrderHelper::getOrderSessionData();
        $marketplace = $order['marketplace'] ?? false;
        if ( $marketplace) {
            $marketplace = reset($marketplace);
        }
        $data['paymentObject'] = $this;
        $data['status'] = 'pending';
        $data['formname'] = OrderHelper::getOrderSessionToken();
        $EurobankModel=new EurobankModel();
        if ($marketplace) {
            $data['orderId'] = Arr::get($marketplace, 'created_order_id', 0);
            $data['form_data_array'] = $EurobankModel->createForm($marketplace);
        }else{
            $data['orderId'] = Arr::get($order, 'order_id', 0);
            $data['form_data_array'] = $EurobankModel->createForm($order);
        }
        $data['errorMessage'] = null;
        $data['message'] = 'No Valid Order Provided';
        $data['error'] = false;
        return $data;

    }



}
