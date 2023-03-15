<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->string('locale')->default(config('app.locale'));
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at']);
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        // Roles and Permissions
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        Schema::create('catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('catalog_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('catalog_id');
            $table->string('codigo')->nullable();
            $table->text('value')->nullable();
            $table->text('icon')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();

            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->json('principles');
            $table->json('values');
            $table->enum('type', array('ODS', 'PEI', 'PDOT'));
            $table->integer('start_year')->nullable();
            $table->integer('end_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('plan_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('code', 20)->nullable();
            $table->string('name', 80);
            $table->text('description')->nullable();
            $table->enum('type', array('OBJETIVO'));
            $table->unsignedInteger('axis_id')->nullable();
            $table->unsignedInteger('focus_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('plan_elements')
                ->onDelete('cascade');

            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');

            $table->foreign('axis_id')
                ->references('id')
                ->on('catalog_details')
                ->onDelete('cascade');

            $table->foreign('focus_id')
                ->references('id')
                ->on('catalog_details')
                ->onDelete('cascade');

        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('element_id')->unsigned()->index();
            $table->integer('related_id')->unsigned()->index();

            $table->foreign('element_id')
                ->references('id')
                ->on('plan_elements')
                ->onDelete('cascade');
            $table->foreign('related_id')
                ->references('id')
                ->on('plan_elements')
                ->onDelete('cascade');
        });

        Schema::create('indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('type', 20);
            $table->text('calculation_formula')->nullable();
            $table->text('information')->nullable();
            $table->text('source')->nullable();
            $table->text('goal_description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('f_start_date')->nullable();
            $table->date('f_end_date')->nullable();
            $table->integer('frequency')->nullable();
            $table->string('responsible')->nullable();
            $table->boolean('is_higher_values_best')->nullable();

            $table->unsignedInteger('measure_unit_id')->nullable()->index();
            $table->morphs('indicatorable');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('measure_unit_id')->references('id')->on('catalog_details')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('responsible_unit')->nullable();
            $table->string('project_leader')->nullable();
            $table->text('benefits')->nullable();
            $table->enum('state', ['execution', 'canceled', 'completed','closed','suspended'])->nullable();
            $table->text('risks')->nullable();
            $table->float('physic_advance')->nullable();
            $table->float('referential_budget')->nullable();
            $table->float('executed_budget')->nullable();
            $table->text('location')->nullable();
            $table->text('components')->nullable();
            $table->unsignedInteger('plan_elements_id');
            $table->foreign('plan_elements_id')
                ->references('id')
                ->on('plan_elements')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('indicator_goals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('indicator_id');
            $table->foreign('indicator_id')->references('id')->on('indicators');
            $table->double('goal', 15, 2);
            $table->double('actual', 15, 2)->nullable();
            $table->integer('period');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('year')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {

        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);

        Schema::drop('plan_elements');
        Schema::drop('plans');
        Schema::drop('catalog_details');
        Schema::drop('catalogs');
        Schema::drop('indicator_goals');
        Schema::drop('indicator');

        Schema::drop('settings');
        Schema::drop('users');
    }
}
