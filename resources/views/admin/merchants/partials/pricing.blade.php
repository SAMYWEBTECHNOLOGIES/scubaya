<div style="background: rgb(51, 122, 183);">
    <header class="smaller">
        <div class="container white clearfix">
            <h3 id="logo">Merchant Pricing Settings</h3>
        </div>
    </header>
</div>
<div class="container screen-fit">
    <section>
        <form method="post" action="{{route('scubaya::admin::merchants::merchant_pricing_settings',[$id])}}">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="active_commission" class="control-label" data-toggle="tooltip">Activate commission</label><br>
                        <div class="btn-group" id="active_commission" data-toggle="buttons">
                            <label class="btn btn-default btn-on btn-sm {{!is_null(@$merchant_pricing_settings->active_commission) ? (@$merchant_pricing_settings->active_commission ? 'active':''):'active'}} ">
                                <input type="radio" value="1" name="active_commission" {{!is_null(@$merchant_pricing_settings->active_commission) ? (@$merchant_pricing_settings->active_commission ? 'checked':''):'checked'}} >YES</label>

                            <label class="btn btn-default btn-off btn-sm {{!is_null(@$merchant_pricing_settings->active_commission) ? (!@$merchant_pricing_settings->active_commission ? 'active':''):''}}">
                                <input type="radio" value="0" name="active_commission" {{!is_null(@$merchant_pricing_settings->active_commission) ? (!@$merchant_pricing_settings->active_commission ? 'checked':''):''}} >NO</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="charge_commission_merchant" class="control-label" data-toggle="tooltip">Charge commission when created by merchant</label><br>
                        <div class="btn-group" id="charge_commission_merchant" data-toggle="buttons">
                            <label class="btn btn-default btn-on btn-sm {{!is_null(@$merchant_pricing_settings->charge_commission_merchant) ? (@$merchant_pricing_settings->charge_commission_merchant ? 'active':''):'active'}}">
                                <input type="radio" value="1" name="charge_commission_merchant" {{!is_null(@$merchant_pricing_settings->charge_commission_merchant) ? (@$merchant_pricing_settings->charge_commission_merchant ? 'checked':''):'checked'}} >YES</label>

                            <label class="btn btn-default btn-off btn-sm {{!is_null(@$merchant_pricing_settings->charge_commission_merchant) ? (!@$merchant_pricing_settings->charge_commission_merchant ? 'active':''):''}}">
                                <input type="radio" value="0" name="charge_commission_merchant" {{!is_null(@$merchant_pricing_settings->charge_commission_merchant) ? (!@$merchant_pricing_settings->charge_commission_merchant ? 'checked':''):''}}>NO</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="auto_block" class="control-label" data-toggle="tooltip">Auto block account unpaid invoices</label><br>
                        <div class="btn-group" id="auto_block" data-toggle="buttons">
                            <label class="btn btn-default btn-on btn-sm {{!is_null(@$merchant_pricing_settings->auto_block) ? (@$merchant_pricing_settings->auto_block ? 'active':''):'active'}}">
                                <input type="radio" value="1" name="auto_block" {{!is_null(@$merchant_pricing_settings->auto_block) ? (@$merchant_pricing_settings->auto_block ? 'checked':''):'checked'}}>YES</label>

                            <label class="btn btn-default btn-off btn-sm {{!is_null(@$merchant_pricing_settings->auto_block) ? (!@$merchant_pricing_settings->auto_block ? 'active':''):''}}">
                                <input type="radio" value="0" name="auto_block" {{!is_null(@$merchant_pricing_settings->auto_block) ? (!@$merchant_pricing_settings->auto_block ? 'checked':''):''}}>NO</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="website_level" class="control-label" data-toggle="tooltip">Block on website level not main account level</label><br>
                        <div class="btn-group" id="website_level" data-toggle="buttons">
                            <label class="btn btn-default btn-on btn-sm {{!is_null(@$merchant_pricing_settings->website_level) ? (@$merchant_pricing_settings->website_level ? 'active':''):'active'}}">
                                <input type="radio" value="1" name="website_level" {{!is_null(@$merchant_pricing_settings->website_level) ? (@$merchant_pricing_settings->website_level ? 'checked':''):'checked'}}>YES</label>

                            <label class="btn btn-default btn-off btn-sm {{!is_null(@$merchant_pricing_settings->website_level) ? (!@$merchant_pricing_settings->website_level ? 'active':''):''}}">
                                <input type="radio" value="0" name="website_level" {{!is_null(@$merchant_pricing_settings->website_level) ? (!@$merchant_pricing_settings->website_level ? 'checked':''):''}}>NO</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="unpaid_invoices">Total unpaid invoices before block account</label>
                        <input type="text" class="form-control" value="{{@$merchant_pricing_settings->unpaid_invoices}}" name="unpaid_invoices" id="unpaid_invoices"/>
                    </div>

                    <div class="form-group">
                        <label for="charge_commission_shop" class="control-label" data-toggle="tooltip">Charge commission on shop</label><br>
                        <div class="btn-group" id="charge_commission_shop" data-toggle="buttons">
                            <label class="btn btn-default btn-on btn-sm {{!is_null(@$merchant_pricing_settings->charge_commission_shop) ? (@$merchant_pricing_settings->charge_commission_shop ? 'active':''):'active'}}">
                                <input type="radio" value="1" name="charge_commission_shop" {{!is_null(@$merchant_pricing_settings->charge_commission_shop) ? (@$merchant_pricing_settings->charge_commission_shop ? 'checked':''):'checked'}}>YES</label>

                            <label class="btn btn-default btn-off btn-sm {{!is_null(@$merchant_pricing_settings->charge_commission_shop) ? (!@$merchant_pricing_settings->charge_commission_shop ? 'active':''):''}}">
                                <input type="radio" value="0" name="charge_commission_shop" {{!is_null(@$merchant_pricing_settings->charge_commission_shop) ? (!@$merchant_pricing_settings->charge_commission_shop ? 'checked':''):''}}>NO</label>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="commission_dive_center">Commission amount dive center</label>
                        <input type="text" class="form-control" name="commission_dive_center" value="{{$merchant_pricing_settings->commission_dive_center or ''}}" id="commission_dive_center"/>
                    </div>

                    <div class="form-group">
                        <label for="commission_hotel">Commission amount Hotel</label>
                        <input type="text" class="form-control" name="commission_dive_hotel" value="{{$merchant_pricing_settings->commission_dive_hotel or ''}}"  id="commission_dive_hotel"/>
                    </div>

                    <div class="form-group">
                        <label for="commission_shop">Commission amount shop</label>
                        <input type="text" class="form-control" name="commission_dive_shop" value="{{$merchant_pricing_settings->commission_dive_shop or ''}}" id="commission_dive_shop"/>
                    </div>
                    <div class="form-group">
                        <label for="commission_percentage">Commission Percentage</label>
                        <input type="text" class="form-control" name="commission_percentage" value="" id="commission_percentage"/>
                    </div>

                </div>
            </div>

            <div class="box-footer">
                <a href="#">
                    <button type="button" class="btn btn-default pull-right">Cancel</button>
                </a>
                <button type="submit" class="btn btn-info pull-right">Save</button>
            </div>

        </form>
    </section>
</div>