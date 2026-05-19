<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MasterClass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create([
            'title' => 'Резьба по дереву',
            'description' => 'Мастер-классы по работе с деревом.',
        ]);
    }

    private function createUser(string $role, string $email, string $phone): User
    {
        return User::create([
            'name' => $role === 'leader' ? 'Ведущий Тестовый' : 'Посетитель Тестовый',
            'email' => $email,
            'phone' => $phone,
            'password' => '12345678',
            'role' => $role,
        ]);
    }

    private function createMasterClass(int $capacity = 2): MasterClass
    {
        $category = $this->createCategory();
        $leader = $this->createUser('leader', 'leader@example.com', '+79990000010');

        return MasterClass::create([
            'category_id' => $category->id,
            'leader_id' => $leader->id,
            'title' => 'Основы резьбы',
            'description' => 'Учимся работать с инструментами.',
            'class_date' => now()->addDays(4)->toDateString(),
            'time_slot' => '15:00',
            'capacity' => $capacity,
            'price' => 2000,
        ]);
    }

    public function test_guest_does_not_see_booking_button_on_category_page(): void
    {
        $masterClass = $this->createMasterClass();

        $response = $this->get(route('category.show', $masterClass->category_id));

        $response
            ->assertOk()
            ->assertSee('Основы резьбы')
            ->assertDontSee('записаться');
    }

    public function test_visitor_can_open_booking_confirmation_page(): void
    {
        $masterClass = $this->createMasterClass();
        $visitor = $this->createUser('visitor', 'visitor@example.com', '+79990000011');

        $response = $this->actingAs($visitor)->get(route('booking.confirm', $masterClass->id));

        $response
            ->assertOk()
            ->assertSee('Подтверждение записи')
            ->assertSee('Основы резьбы')
            ->assertSee('Посетитель Тестовый');
    }

    public function test_visitor_can_book_master_class_when_places_are_available(): void
    {
        $masterClass = $this->createMasterClass();
        $visitor = $this->createUser('visitor', 'visitor@example.com', '+79990000012');

        $response = $this->actingAs($visitor)->post(route('booking.store', $masterClass->id));

        $response->assertRedirect(route('category.show', $masterClass->category_id));

        $this->assertDatabaseHas('registrations', [
            'master_class_id' => $masterClass->id,
            'user_id' => $visitor->id,
        ]);
    }

    public function test_visitor_cannot_book_same_master_class_twice(): void
    {
        $masterClass = $this->createMasterClass();
        $visitor = $this->createUser('visitor', 'visitor@example.com', '+79990000013');

        Registration::create([
            'master_class_id' => $masterClass->id,
            'user_id' => $visitor->id,
        ]);

        $response = $this->actingAs($visitor)->post(route('booking.store', $masterClass->id));

        $response
            ->assertRedirect(route('category.show', $masterClass->category_id))
            ->assertSessionHas('errorMessage', 'Вы уже записаны на этот мастер-класс.');

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_visitor_cannot_book_when_there_are_no_free_places(): void
    {
        $masterClass = $this->createMasterClass(capacity: 1);
        $firstVisitor = $this->createUser('visitor', 'first@example.com', '+79990000014');
        $secondVisitor = $this->createUser('visitor', 'second@example.com', '+79990000015');

        Registration::create([
            'master_class_id' => $masterClass->id,
            'user_id' => $firstVisitor->id,
        ]);

        $response = $this->actingAs($secondVisitor)->post(route('booking.store', $masterClass->id));

        $response
            ->assertRedirect(route('category.show', $masterClass->category_id))
            ->assertSessionHas('errorMessage', 'Свободных мест больше нет.');

        $this->assertDatabaseMissing('registrations', [
            'master_class_id' => $masterClass->id,
            'user_id' => $secondVisitor->id,
        ]);
    }

    public function test_home_page_shows_visitor_previous_bookings(): void
    {
        $masterClass = $this->createMasterClass();
        $visitor = $this->createUser('visitor', 'visitor@example.com', '+79990000016');

        Registration::create([
            'master_class_id' => $masterClass->id,
            'user_id' => $visitor->id,
        ]);

        $response = $this->actingAs($visitor)->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('Мои записи')
            ->assertSee('Основы резьбы');
    }
}
