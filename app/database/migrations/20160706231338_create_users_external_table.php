<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUsersExternalTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('users_external', function($table) {
            $table->increments('id');
            $table->string('source');
            $table->string('source_user_id');
            $table->integer('user_id');
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('users_external');
    }
}
