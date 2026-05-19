<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create([
            'title' => 'Кулинария',
            'description' => 'Мастер-классы по приготовлению блюд.',
        ]);
    }

    private function createLeader(string $email = 'leader@example.com'): User
    {
        return User::create([
            'name' => 'Иванова Ольга Ивановна',
            'email' => $email,
            'phone' => '+7999'.random_int(1000000, 9999999),
            'password' => '12345678',
            'role' => 'leader',
        ]);
    }

    private function createVisitor(string $email = 'visitor@example.com'): User
    {
        return User::create([
            'name' => 'Иванов Иван Иванович',
            'email' => $email,
            'phone' => '+7988'.random_int(1000000, 9999999),
            'password' => '12345678',
            'role' => 'visitor',
        ]);
    }

    public function test_leader_can_create_master_class(): void
    {
        $category = $this->createCategory();
        $leader = $this->createLeader();

        $response = $this->actingAs($leader)->post(route('master-class.store'), [
            'category_id' => $category->id,
            'title' => 'Роспись пряников',
            'description' => 'Учимся расписывать имбирные пряники.',
            'class_date' => now()->addDays(3)->toDateString(),
            'time_slot' => '09:00',
            'capacity' => 10,
            'price' => 1500,
        ]);

        $response->assertRedirect(route('cabinet'));

        $this->assertDatabaseHas('master_classes', [
            'leader_id' => $leader->id,
            'category_id' => $category->id,
            'title' => 'Роспись пряников',
            'time_slot' => '09:00',
            'capacity' => 10,
            'price' => 1500,
        ]);
    }

    public function test_visitor_cannot_open_master_class_create_page(): void
    {
        $visitor = $this->createVisitor();

        $response = $this->actingAs($visitor)->get(route('master-class.create'));

        $response->assertRedirect(route('home'));
    }

    public function test_master_class_is_not_created_when_required_fields_are_empty(): void
    {
        $leader = $this->createLeader();

        $response = $this->actingAs($leader)
            ->from(route('master-class.create'))
            ->post(route('master-class.store'), []);

        $response
            ->assertRedirect(route('master-class.create'))
            ->assertSessionHasErrors([
                'category_id',
                'title',
                'description',
                'class_date',
                'time_slot',
                'capacity',
                'price',
            ]);

        $this->assertDatabaseCount('master_classes', 0);
    }

    public function test_busy_date_and_time_cannot_be_used_twice(): void
    {
        $category = $this->createCategory();
        $leader = $this->createLeader();
        $date = now()->addDays(5)->toDateString();

        MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $leader->id,
            'title' => 'Занятый слот',
            'description' => 'Первый мастер-класс.',
            'class_date' => $date,
            'time_slot' => '11:00',
            'capacity' => 8,
            'price' => 1200,
        ]);

        $response = $this->actingAs($leader)->post(route('master-class.store'), [
            'category_id' => $category->id,
            'title' => 'Повторный слот',
            'description' => 'Этот мастер-класс не должен сохраниться.',
            'class_date' => $date,
            'time_slot' => '11:00',
            'capacity' => 8,
            'price' => 1200,
        ]);

        $response->assertSessionHasErrors('time_slot');
        $this->assertDatabaseCount('master_classes', 1);
    }

    public function test_cabinet_shows_only_current_leader_master_classes(): void
    {
        $category = $this->createCategory();
        $currentLeader = $this->createLeader('current@example.com');
        $otherLeader = $this->createLeader('other@example.com');

        MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $currentLeader->id,
            'title' => 'Мой мастер-класс',
            'description' => 'Описание моего мастер-класса.',
            'class_date' => now()->addDays(7)->toDateString(),
            'time_slot' => '13:00',
            'capacity' => 10,
            'price' => 1000,
        ]);

        MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $otherLeader->id,
            'title' => 'Чужой мастер-класс',
            'description' => 'Описание чужого мастер-класса.',
            'class_date' => now()->addDays(8)->toDateString(),
            'time_slot' => '15:00',
            'capacity' => 10,
            'price' => 1000,
        ]);

        $response = $this->actingAs($currentLeader)->get(route('cabinet'));

        $response
            ->assertOk()
            ->assertSee('Мой мастер-класс')
            ->assertDontSee('Чужой мастер-класс');
    }

    public function test_leader_can_update_master_class_description_and_price(): void
    {
        $category = $this->createCategory();
        $leader = $this->createLeader();

        $masterClass = MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $leader->id,
            'title' => 'Лепка из глины',
            'description' => 'Старое описание.',
            'class_date' => now()->addDays(10)->toDateString(),
            'time_slot' => '09:00',
            'capacity' => 6,
            'price' => 900,
        ]);

        $response = $this->actingAs($leader)->post(route('master-class.update', $masterClass->id), [
            'description' => 'Новое описание.',
            'price' => 1300,
        ]);

        $response->assertRedirect(route('master-class.show', $masterClass->id));

        $this->assertDatabaseHas('master_classes', [
            'id' => $masterClass->id,
            'description' => 'Новое описание.',
            'price' => 1300,
        ]);
    }
}
