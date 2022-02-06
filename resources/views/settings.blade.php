@php
     $eurobankStatus = get_payment_setting('status', EUROBANK_PAYMENT_METHOD_NAME);

 @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/Eurobank/images/eurobank.png') }}"
                 alt="Eurobank">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://Eurobank.com" target="_blank">Eurobank</a>
                    <p>{{ __('Customer can buy product and pay directly using Visa, Credit card via Eurobank') }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div
                    class="payment-name-label-group @if (get_payment_setting('status', EUROBANK_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', EUROBANK_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($eurobankStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($eurobankStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', EUROBANK_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Eurobank']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Eurobank']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://Eurobank.com" target="_blank">
                                        {{ __('Register an account on Eurobank') }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have Client ID, Client Secret', ['name' => 'Eurobank']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter Client ID, Secret into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="Eurobank_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_name"
                                   id="Eurobank_name" data-counter="400"
                                   value="{{ get_payment_setting('name', EUROBANK_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'Eurobank'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_description" id="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_description">{{ get_payment_setting('description', EUROBANK_PAYMENT_METHOD_NAME, __('Payment with Eurobank')) }}</textarea>
                        </div>

                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank"
                                                                                                  href="https://Eurobank.com">Eurobank</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="Eurobank_key">{{ __('Key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_key" id="Eurobank_key"
                                   value="{{ get_payment_setting('key', EUROBANK_PAYMENT_METHOD_NAME) }}" placeholder="012344568">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="Eurobank_secret">{{ __('Secret') }}</label>
                            <input type="password" class="next-input" placeholder="••••••••" id="Eurobank_secret"
                                   name="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_secret"
                                   value="{{ get_payment_setting('secret', EUROBANK_PAYMENT_METHOD_NAME) }}">
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, EUROBANK_PAYMENT_METHOD_NAME) !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($eurobankStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($eurobankStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($eurobankStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>

{{--@php $eurobankStatus = setting('payment_eurobank_status'); @endphp--}}
{{--<table class="table payment-method-item">--}}
{{--    <tbody>--}}
{{--    <tr class="border-pay-row">--}}
{{--        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>--}}
{{--        <td style="width: 20%;">--}}
{{--            <img class="filter-black" src="{{ url('vendor/core/plugins/payment/images/eurobank.png') }}" alt="Eurobank">--}}
{{--        </td>--}}
{{--        <td class="border-right">--}}
{{--            <ul>--}}
{{--                <li>--}}
{{--                    <a href="https://cardlink.gr/" target="_blank">Eurobank</a>--}}
{{--                    <p>{{ trans('plugins/payment::payment.eurobank_description') }}</p>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr class="bg-white">--}}
{{--        <td colspan="3">--}}
{{--            <div class="float-start" style="margin-top: 5px;">--}}
{{--                <div class="payment-name-label-group @if ($eurobankStatus == 0) hidden @endif">--}}
{{--                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label--}}
{{--                        class="ws-nm inline-display method-name-label">{{ setting('payment_eurobank_name') }}</label>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="float-end">--}}
{{--                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($eurobankStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>--}}
{{--                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($eurobankStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>--}}
{{--            </div>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr class="paypal-online-payment payment-content-item hidden">--}}
{{--        <td class="border-left" colspan="3">--}}
{{--            {!! Form::open() !!}--}}
{{--            {!! Form::hidden('type', \Botble\Payment\Enums\PaymentMethodEnum::EUROBANK, ['class' => 'payment_type']) !!}--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6">--}}
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Eurobank']) }}</label>--}}
{{--                        </li>--}}
{{--                        <li class="payment-note">--}}
{{--                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Eurobank']) }}--}}
{{--                                :</p>--}}
{{--                            <ul class="m-md-l" style="list-style-type:decimal">--}}
{{--                                <li style="list-style-type:decimal">--}}
{{--                                    <a href="https://cardlink.gr/" target="_blank">--}}
{{--                                        {{ trans('plugins/payment::payment.service_registration', ['name' => 'Eurobank']) }}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li style="list-style-type:decimal">--}}
{{--                                    <p>{{ trans('plugins/payment::payment.eurobank_after_service_registration_msg', ['name' => 'Eurobank']) }}</p>--}}
{{--                                </li>--}}
{{--                                <li style="list-style-type:decimal">--}}
{{--                                    <p>{{ trans('plugins/payment::payment.eurobank_enter_client_id_and_secret') }}</p>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div class="col-sm-6">--}}
{{--                    <div class="well bg-white">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field"--}}
{{--                                   for="eurobank_name">{{ trans('plugins/payment::payment.method_name') }}</label>--}}
{{--                            <input type="text" class="next-input input-name"--}}
{{--                                   name="payment_eurobank_name" id="eurobank_name" data-counter="400"--}}
{{--                                   value="{{ setting('payment_eurobank_name', trans('plugins/payment::payment.pay_online_via', ['name' => 'Eurobank'])) }}">--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field"--}}
{{--                                   for="payment_eurobank_description">{{ trans('core/base::forms.description') }}</label>--}}
{{--                            <textarea class="next-input" name="payment_eurobank_description"--}}
{{--                                      id="payment_eurobank_description">{{ get_payment_setting('description', 'eurobank', __('Payment with Eurobank')) }}</textarea>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field"--}}
{{--                                   for="payment_eurobank_description">{{ trans('plugins/payment::payment.installments') }}</label>--}}
{{--                            @php--}}
{{--                                $payment_eurobank_installments=setting('payment_eurobank_installments');--}}
{{--                            @endphp--}}
{{--                            <select class="next-input" name="payment_eurobank_installments"--}}
{{--                                    id="payment_eurobank_installments">--}}
{{--                                @for($x=0; $x<=36; $x++)--}}
{{--                                    <option--}}
{{--                                        value="{{$x}}" {{$x==$payment_eurobank_installments?'selected="selected"':''}}>{{$x}}</option>--}}
{{--                                @endfor--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <p class="payment-note">--}}
{{--                            {{ trans('plugins/payment::payment.please_provide_information') }} <a--}}
{{--                                target="_blank" href="https://cardlink.gr/">Eurobank</a>:--}}
{{--                        </p>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field"--}}
{{--                                   for="eurobank_client_id">{{ trans('plugins/payment::payment.eurobank_key') }}</label>--}}
{{--                            <input type="text" class="next-input" name="payment_eurobank_client_id"--}}
{{--                                   id="eurobank_client_id" placeholder="0024077786"--}}
{{--                                   value="{{ app()->environment('demo') ? '*******************************' : setting('payment_eurobank_client_id') }}">--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field"--}}
{{--                                   for="eurobank_secret">{{ trans('plugins/payment::payment.eurobank_secret') }}</label>--}}
{{--                            <div class="input-option">--}}
{{--                                <input type="password" class="next-input" id="eurobank_secret"--}}
{{--                                       name="payment_eurobank_secret" placeholder="*************"--}}
{{--                                       value="{{ app()->environment('demo') ? '*******************************' : setting('payment_eurobank_secret') }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'eurobank') !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-12 bg-white text-end">--}}
{{--                <button--}}
{{--                    class="btn btn-warning disable-payment-item @if ($eurobankStatus == 0) hidden @endif"--}}
{{--                    type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>--}}
{{--                <button--}}
{{--                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($eurobankStatus == 1) hidden @endif"--}}
{{--                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>--}}
{{--                <button--}}
{{--                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($eurobankStatus == 0) hidden @endif"--}}
{{--                    type="button">{{ trans('plugins/payment::payment.update') }}</button>--}}
{{--            </div>--}}
{{--            {!! Form::close() !!}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    </tbody>--}}
{{--</table>--}}

