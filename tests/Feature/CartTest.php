<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_page_can_be_accessed()
    {
        Product::factory()->count(3)->create();

        $this->get('/cart')->assertViewIs('cart');
    }

    /** @test */
    public function item_can_be_added_to_the_cart()
    {
        Product::factory()->count(3)->create();

        $this->post('/cart', ['id' => 1])->assertRedirect('/cart')->assertSessionHasNoErrors()
            ->assertSessionHas('cart.0', [
                'id'  => 1,
                'qty' => 1,
            ]);
    }

    /** @test */
    public function same_item_cannot_be_added_to_the_cart_twice()
    {
        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        $this->post('/cart', ['id' => 1]); // Taco
        $this->post('/cart', ['id' => 1]); // Taco
        $this->post('/cart', ['id' => 2]); // Pizza

        $this->assertEquals(2, count(session('cart')));
    }
}
