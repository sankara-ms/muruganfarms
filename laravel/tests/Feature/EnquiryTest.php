<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_enquiry_requires_valid_input(): void
    {
        $response = $this->postJson('/api/enquiries', [
            'name' => 'A',
            'phone' => '123',
            'product' => 'Rice / Paddy',
            'message' => 'Hi',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'phone', 'message']);
    }

    public function test_enquiry_can_be_created(): void
    {
        $response = $this->postJson('/api/enquiries', [
            'name' => 'Murugan',
            'phone' => '+91 98765 43210',
            'product' => 'Rice / Paddy',
            'message' => 'I want to buy rice for my home.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Murugan')
            ->assertJsonPath('data.product', 'Rice / Paddy');

        $this->assertDatabaseHas('enquiries', [
            'name' => 'Murugan',
            'product' => 'Rice / Paddy',
        ]);
    }
}
