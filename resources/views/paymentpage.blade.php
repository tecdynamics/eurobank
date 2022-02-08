@php
     $totalInstalments =   get_payment_setting('installments', EUROBANK_PAYMENT_METHOD_NAME);
@endphp
<li class="list-group-item">
    <input class="magic-radio js_payment_method" type="radio" name="payment_method"
           id="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}"
           value="{{ EUROBANK_PAYMENT_METHOD_NAME }}" data-bs-toggle="collapse"
           data-bs-target=".payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_wrap"
           data-parent=".list_payment_method"
           @if (setting('default_payment_method') == EUROBANK_PAYMENT_METHOD_NAME) checked @endif
    >
    <label
        for="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', EUROBANK_PAYMENT_METHOD_NAME) }}</label>
    <div
        class="payment_{{ EUROBANK_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == EUROBANK_PAYMENT_METHOD_NAME) show @endif">
        @if ($errorMessage)
            <div class="text-danger my-2">
                {!! clean($errorMessage) !!}
            </div>
        @else
            <p>{!! get_payment_setting('description', EUROBANK_PAYMENT_METHOD_NAME, __('Payment with Eurobank')) !!}</p>
        @endif
<label for="instalments"> <?php echo __('Instalments'); ?></label><br/>
<select name="eurobankinstallments" id="instalments" class="form-control">
    @for ($x = 1; $x <= (int)$totalInstalments; $x++)
    <option value="{{$x}}"  {{($x == 1)?'selected="selected"':''}}>{{$x}}</option>
        @endfor
</select>
 <input type="hidden" id="order_id" name="_order_id" value="{{ $orderId }}">
<input type="hidden" id="{{$paymentId}}_type" name="_type" value="{{EUROBANK_PAYMENT_METHOD_NAME}}">
    </div>
