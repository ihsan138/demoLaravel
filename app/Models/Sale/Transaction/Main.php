<?php

namespace App\Models\Sale\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Main extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'sale_transaction_main'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'date_ordered','date_warranty','date_delivery_order','order_place','order_number','sale_receipt_number','sale_delivery_order_number','sale_invoice_number','attention_to','customer_name','customer_email','customer_website','shipping_name','shipping_address1','shipping_address2','shipping_address3','shipping_address4','shipping_address5','shipping_phone_number','billing_name','billing_address1','billing_address2','billing_address3','billing_address4','billing_address5','billing_phone_number','payment_method','payment_references_no','selected_currency','selected_forex','summary_subtotal','summary_total_in_myr','shipping_provider','shipment_type','shipping_provider_type','tracking_code','status','remarks','refund_amount','refund_remarks'
        //'sale_order_number
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'sale_order_number' => 'required|min:3|alpha_num_spaces|unique:sale_transaction_main,sale_order_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    ];

    /**
     * Relationship Child Multiple
     */
    public function items(){
        return $this->hasMany('App\Models\Sale\Transaction\Item','fk_sale_order_number','sale_order_number'); //overide foreign key and local key
    }
}
