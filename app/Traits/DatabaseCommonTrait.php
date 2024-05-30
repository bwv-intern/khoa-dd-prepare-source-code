<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait DatabaseCommonTrait {
    public function commonColumns(Blueprint $table) {
        $table->tinyInteger('del_flg')->nullable(false)->default(0);
        $table->datetime('created_at');
        $table->unsignedBigInteger('created_by');
        $table->datetime('updated_at')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->datetime('deleted_at')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
    }

    public function commonCharset(Blueprint $table) {
        $table->charset = 'utf8';
        $table->collation = 'utf8_general_ci';
    }
}
