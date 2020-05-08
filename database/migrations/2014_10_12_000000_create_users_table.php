<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Dynamo DB
        $params = [
            'TableName' => 'Users',
            'KeySchema' => [
                [
                    'AttributeName' => 'year',
                    'KeyType' => 'HASH'  //Partition key
                ],
                [
                    'AttributeName' => 'title',
                    'KeyType' => 'RANGE'  //Sort key
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'year',
                    'AttributeType' => 'N'
                ],
                [
                    'AttributeName' => 'title',
                    'AttributeType' => 'S'
                ],

            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ]
        ];
        \BaoPham\DynamoDb\Facades\DynamoDb::client()->createTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
