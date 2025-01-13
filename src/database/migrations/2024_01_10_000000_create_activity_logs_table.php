<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This method is executed when the migration is run.
     * It creates a new table in the database based on the settings in the config file.
     */
    public function up()
    {
        // Retrieve the table name from the config file, or use a default name if not specified
        $tableName = config('activity-tracker.database.table', 'activity_logs');

        // Retrieve the database connection from the config file
        $connection = config('activity-tracker.database.connection');

        // Create the table in the database using the Schema Builder
        Schema::connection($connection)->create($tableName, function (Blueprint $table) {
            // Column for the primary key (ID)
            $table->id();

            // Column for storing the subject of the activity (e.g., "User Login" or "Data Update")
            $table->string('subject');

            // Column to store the morph relationship to the "causer" (the user or entity that triggered the activity)
            $table->nullableMorphs('causer');

            // Column to store the morph relationship to the "model" (the affected object, e.g., product, project)
            $table->nullableMorphs('model');

            // Column to store the type of event (e.g., "Login", "Update")
            $table->string('event')->nullable();

            // Column to store the state of the model before the activity (in JSON format)
            $table->json('before')->nullable();

            // Column to store the state of the model after the activity (in JSON format)
            $table->json('after')->nullable();

            // Column for storing the IP address of the visitor
            $table->string('ip')->nullable();

            // Column for storing the HTTP headers of the request (in JSON format)
            $table->json('headers')->nullable();

            // Column for storing query parameters sent with the request (in JSON format)
            $table->json('query_parameters')->nullable();

            // Column for storing the HTTP request method (e.g., GET, POST)
            $table->string('request_method')->nullable();

            // Column for storing the browser type used by the visitor
            $table->string('browser')->nullable();

            // Column for storing the device type used by the visitor
            $table->string('device_type')->nullable();

            // Column for storing the operating system of the visitor's device
            $table->string('operating_system')->nullable();

            // Column for storing the referring URL (the page that directed the user to the current page)
            $table->string('referring_url')->nullable();

            // Column for storing the current URL of the page being accessed
            $table->string('current_url')->nullable();

            // Column for storing a description of the activity
            $table->text('description')->nullable();

            // Column for storing additional properties related to the activity (in JSON format)
            $table->json('properties')->nullable();

            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * This method is executed when the migration is rolled back.
     * It drops the table from the database.
     */
    public function down()
    {
        // Retrieve the table name from the config file, or use the default name if not specified
        $tableName = config('activity-tracker.database.table', 'activity_logs');

        // Retrieve the database connection from the config file
        $connection = config('activity-tracker.database.connection');

        // Drop the table from the database if it exists
        Schema::connection($connection)->dropIfExists($tableName);
    }
};
