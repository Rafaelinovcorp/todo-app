<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Pest Test Configuration
|--------------------------------------------------------------------------
|
| Aqui ligamos o Pest ao TestCase do Laravel
|
*/

uses(TestCase::class, RefreshDatabase::class)
    ->in('Feature');
