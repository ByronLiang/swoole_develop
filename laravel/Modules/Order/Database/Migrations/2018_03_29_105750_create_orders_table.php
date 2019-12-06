<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->down();

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount')->comment('支付金额');
            $table->string('status')->comment('状态');
            $table->string('after_sale_status')->default(0)->comment('售后状态');
            $table->timestamp('paid_at')->nullable()->comment('付款时间');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        //发票
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable()->comment('发票抬头:个人，企业');
            $table->string('phone')->nullable()->comment('收票人手机');
            $table->string('company')->nullable()->comment('抬头明细');
            $table->string('company_num')->nullable()->comment('企业税号');
            $table->string('file')->nullable()->comment('发票文件地址:图片&pdf');
            $table->timestamps();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        //物流
        Schema::create('order_logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->comment('收货姓名');
            $table->string('phone')->comment('收货电话');
            $table->string('address')->comment('收货地址');
            $table->decimal('amount')->comment('快递费用');
            $table->string('number')->nullable()->comment('物流单号');
            $table->string('company')->nullable()->comment('物流公司');
            $table->timestamp('confirm_receipt_at')->nullable()->comment('确认收货时间');
            $table->timestamps();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        //产品
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('名称');
            $table->decimal('price')->nullable()->comment('单价');
            $table->string('image')->nullable()->comment('主图');
            $table->unsignedInteger('quantity')->nullable()->comment('数量');
            $table->decimal('subtotal')->nullable()->comment('小计:单价*数量');
            $table->timestamps();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->morphs('able');
        });

        // 订单操作记录， 包含产品相关记录
        Schema::create('order_operation_records', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->string('group_key')->default('')->index()->comment('记录分组key');
            $table->text('extend')->nullable()->comment('扩展数据|字段');
            $table->timestamps();

            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->morphs('able');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('order_operation_records');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_logistics');
        Schema::dropIfExists('order_invoices');
        Schema::dropIfExists('orders');
    }
}
