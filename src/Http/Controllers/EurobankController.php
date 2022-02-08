<?php

namespace Botble\Eurobank\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Botble\Eurobank\Services\Models\EurobankModel;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use OrderHelper;
use Throwable;

class EurobankController extends BaseController {
    /**
     * @Function   paymentredirect
     * @param Request $request
     * @param BaseHttpResponse $response
     * @Author    : Michail Fragkiskos
     * @Created at: 08/02/2022 at 19:46
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return array|string
     */
    public function paymentredirect(Request $request, BaseHttpResponse $response) {
        $order = OrderHelper::getOrderSessionData();
        $order['installments'] = $request->input(['eurobankinstallments'], 0);
        $order = OrderHelper::setOrderSessionData(OrderHelper::getOrderSessionToken(), $order);
        if (!$order) {
            $data['message'] = 'No Valid Order Provided';
            $data['error'] = true;
            return $data;
        }
        $marketplace = $order['marketplace'] ?? false;
        if ($marketplace) {
            $marketplace = reset($marketplace);
        } else {
            $marketplace = $order;
        }
        $EurobankModel = new EurobankModel();
        $data['paymentObject'] = $EurobankModel;
        $data['status'] = 'pending';
        $data['formname'] = OrderHelper::getOrderSessionToken();
        if ($marketplace) {
            $data['orderId'] = Arr::get($marketplace, 'created_order_id', 0);
            $data['form_data_array'] = $EurobankModel->createForm($marketplace);
        } else {
            $data['orderId'] = Arr::get($order, 'order_id', 0);
            $data['form_data_array'] = $EurobankModel->createForm($order);
        }
        $data['errorMessage'] = null;

        Assets::addStylesDirectly(['css/vendors/normalize.css'])
            ->addStylesDirectly(['css/vendors/bootstrap.min.css'])
            ->addStylesDirectly(['css/vendors/uicons-regular-straight.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/edit-product.js',
            ]);
        return view('plugins/eurobank::redirect', $data)->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function paymentCallback(Request $request, BaseHttpResponse $response) {
        /* +request: Symfony\Component\HttpFoundation\InputBag {#46 ▼
    #parameters: array:13 [▼
      "version" => "2"
      "mid" => "0024077786"
      "orderid" => "51at20220206092816000000"
      "status" => "AUTHORIZED"
      "orderAmount" => "0.1"
      "currency" => "EUR"
      "paymentTotal" => "0.1"
      "message" => "OK, 00 - Approved"
      "riskScore" => "0"
      "payMethod" => "mastercard"
      "txId" => "92639547133311"
      "paymentRef" => "100386"
      "digest" => "Emta7v/M2D3MjjXnLfdi3VPUZIz9ExVQTrKyycZSlZQ="
    ]
  }
        array:13 [▼
  "version" => "2"
  "mid" => "0024077786"
  "orderid" => "51at20220206092816000000"
  "status" => "AUTHORIZED"
  "orderAmount" => "0.1"
  "currency" => "EUR"
  "paymentTotal" => "0.1"
  "message" => "OK, 00 - Approved"
  "riskScore" => "0"
  "payMethod" => "mastercard"
  "txId" => "92639547133311"
  "paymentRef" => "100386"
  "digest" => "Emta7v/M2D3MjjXnLfdi3VPUZIz9ExVQTrKyycZSlZQ="
]
array:3 [▼
  "gateway" => "Eurobank"
  "result" => "success"
  "id" => "51"
]*/
         $data = $_POST ?? [];
        $getData = $_GET ?? [];
//        $getData = [
//            "gateway" => "Eurobank",
//            "result" => "success"
//        ];
//        $data= [
//  "version" => "2",
//  "mid" => "0024077786",
//  "orderid" => "72at20220206092816000000",
//  "status" => "AUTHORIZED",
//  "orderAmount" => "0.1",
//  "currency" => "EUR",
//  "paymentTotal" => "0.1",
//  "message" => "OK, 00 - Approved",
//  "riskScore" => "0",
//  "payMethod" => "mastercard",
//  "txId" => "92639547133311",
//  "paymentRef" => "100386",
//  "digest" => "Emta7v/M2D3MjjXnLfdi3VPUZIz9ExVQTrKyycZSlZQ="
//];
        try {
            $EurobankModel = new EurobankModel();
            //check if is success or not the transaction;
            if (!isset($getData['result'], $data['status']) || !in_array($data['status'], [$EurobankModel::_CAPTURED, $EurobankModel::_AUTHORIZED])) {
                return $response
                    ->setError()
                    ->setNextUrl(PaymentHelper::getCancelURL())
                    ->setMessage(__('Error when processing payment via Eurobank!'));
            }

            if (!$EurobankModel->validate_eb_PayMerchant_responce($data)) {
                return $response
                    ->setError()
                    ->setNextUrl(PaymentHelper::getCancelURL())
                    ->setMessage(__('Payment failed!'));
            }
            $orderInfo = explode('at', $data['orderid']);
            $order_id = (int)reset($orderInfo);
            $status = PaymentStatusEnum::PENDING;

            if (in_array($data['status'], [$EurobankModel::_CAPTURED, $EurobankModel::_AUTHORIZED])) {
                $status = PaymentStatusEnum::COMPLETED;
            }

            $order = Order::Where('id', '=', (int)$order_id)->first();

            //set historical data
            \DB::table('ec_order_histories')->insert([
                'action' => 'confirm_payment',
                'order_id' => (int) $order->id,
                'user_id' => (int)$order->user->id,
                'description' => $data['message'] ?? 'Eurobank Checkout Error',
                'extras' => $data['paymentRef'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
             do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                'amount' => $data['paymentTotal'] ?? 0,
                'currency' => $data['currency'] ?? 'EUR',
                'charge_id' => $data['txId'] ?? $data['orderid'],
                'payment_channel' => EUROBANK_PAYMENT_METHOD_NAME,
                'status' => $status,
                'customer_id' => (int)$order->user->id,
                'customer_type' => Customer::class,
                'payment_type' => $data['payMethod'] ?? 'Card',
                'order_id' => (int)$order->id,
            ]);

            return $response
                ->setNextUrl(route('public.checkout.success', $order->token))
                ->setMessage(__('Checkout successfully!'));

        }
        catch (Exception $x) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        }
    }


}
