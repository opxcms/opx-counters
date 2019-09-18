<?php

use Illuminate\Support\Facades\Schema;
use Core\Foundation\Database\OpxBlueprint;
use Core\Foundation\Database\OpxMigration;

class CreateCountersTable extends OpxMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('counters', static function (OpxBlueprint $table) {
            $table->id();
            $table->boolean('enabled')->nullable()->default(1);
            $table->name();
            $table->content();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('counters');
    }
}
