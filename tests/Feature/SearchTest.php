<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function test_food_search_page_is_accessible()
    {
        $this->get('/')->assertOk();
    }

    public function test_food_search_page_has_all_the_required_page_data()
    {
        //  Arrange phase
        Product::factory()->count(3)->create();

        // Act
        $response = $this->get('/');

        // Assert
        $items = Product::get();

        $response->assertViewIs('search')->assertViewHas('items', $items);
    }

    public function test_food_search_page_shows_the_items()
    {
        Product::factory()->count(3)->create();

        $items = Product::get();

        $this->get('/')->assertSeeInOrder([
            $items[0]->name,
            $items[1]->name,
            $items[2]->name,
        ]);
    }

    /** @test */
    public function food_can_be_searched_given_a_query()
    {
        Product::factory()->create(['name' => 'Taco']);
        Product::factory()->create(['name' => 'Pizza']);
        Product::factory()->create(['name' => 'BBQ']);

        $this->get('/?query=bbq')->assertSee('BBQ')->assertDontSeeText('Pizza')->assertDontSeeText('Taco');
        $this->get('/')->assertSeeInOrder(['Taco', 'Pizza', 'BBQ']);
    }
}
