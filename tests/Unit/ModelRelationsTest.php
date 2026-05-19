<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\MasterClass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_helpers_return_correct_values(): void
    {
        $leader = User::create([
            'name' => 'Ведущий',
            'email' => 'leader@example.com',
            'phone' => '+79990000100',
            'password' => '12345678',
            'role' => 'leader',
        ]);

        $visitor = User::create([
            'name' => 'Посетитель',
            'email' => 'visitor@example.com',
            'phone' => '+79990000101',
            'password' => '12345678',
            'role' => 'visitor',
        ]);

        $this->assertTrue($leader->isLeader());
        $this->assertFalse($leader->isVisitor());
        $this->assertTrue($visitor->isVisitor());
        $this->assertFalse($visitor->isLeader());
    }

    public function test_master_class_counts_free_places(): void
    {
        $category = Category::create([
            'title' => 'Архитектурное моделирование',
            'description' => 'Описание направления.',
        ]);

        $leader = User::create([
            'name' => 'Ведущий',
            'email' => 'leader@example.com',
            'phone' => '+79990000102',
            'password' => '12345678',
            'role' => 'leader',
        ]);

        $visitor = User::create([
            'name' => 'Посетитель',
            'email' => 'visitor@example.com',
            'phone' => '+79990000103',
            'password' => '12345678',
            'role' => 'visitor',
        ]);

        $masterClass = MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $leader->id,
            'title' => 'Макет дома',
            'description' => 'Создаём макет дома.',
            'class_date' => now()->addDays(2)->toDateString(),
            'time_slot' => '09:00',
            'capacity' => 2,
            'price' => 1000,
        ]);

        Registration::create([
            'master_class_id' => $masterClass->id,
            'user_id' => $visitor->id,
        ]);

        $this->assertSame(1, $masterClass->freePlaces());
        $this->assertTrue($masterClass->hasFreePlaces());
    }
}
