<?php

namespace Tests\Support;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected string $baseUrl = '/api/v1/users';
}
