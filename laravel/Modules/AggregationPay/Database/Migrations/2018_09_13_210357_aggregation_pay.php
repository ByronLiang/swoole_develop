<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AggregationPay extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('ap_payment_records', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount');
            $table->decimal('refunded_amount')->default(0);
            $table->string('remark');
            $table->string('channel')->comment('支付渠道');
            $table->enum('status', ['paying', 'paid', 'refunded']);
            $table->string('payment_no')->nullable()->comment('提交给第三方支付编号');
            $table->string('transaction_no')->nullable()->comment('第三方返回交易流水号');
            $table->timestamps();

            $table->morphs('able');
        });

        Schema::create('ap_refund_records', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount');
            $table->string('remark');
            $table->enum('status', ['refunding', 'refunded', 'failure']);
            $table->string('refund_no')->nullable()->comment('提交给第三方退款编号');
            $table->string('transaction_no')->nullable()->comment('第三方返回交易流水号');
            $table->text('failure_reason')->nullable()->commit('失败原因');
            $table->timestamps();

            $table->morphs('able');

            $table->unsignedInteger('payment_record_id');
            $table->foreign('payment_record_id')->references('id')->on('ap_payment_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ap_refund_records');
        Schema::dropIfExists('ap_payment_records');
    }
}
