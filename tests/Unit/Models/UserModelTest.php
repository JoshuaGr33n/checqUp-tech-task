<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a User model can be correctly converted to a domain entity.
     *
     * @return void
     */
    #[Test]
    public function it_converts_to_domain_entity_correctly()
    {
        $user = User::factory()->create(['name' => 'John', 'surname' => 'Doe', 'email' => 'original@email.com', 'phone' => '1234567890', 'country' => Country::USA->value, 'gender' => Gender::MALE->value, 'password' => Hash::make('password123')]);

        $domainUser = $user->toDomainEntity();

        $this->assertEquals($user->name, $domainUser->getName());
    }
}
