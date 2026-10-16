<?php

namespace Tests\Feature;

use App\Models\Marca;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BusinessRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_registration_creates_the_business_for_its_owner(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Alicia Rivera',
            'email' => 'alicia@example.com',
            'password' => 'secret123',
            'rol' => 'negocio',
            'negocio' => [
                'nombre' => 'Cafetería Luna',
                'telefono' => '5551234567',
                'descripcion' => 'Café de especialidad',
                'rfc' => 'RIVA900101XXX',
                'codigo_postal' => '06000',
            ],
        ]);

        $response->assertCreated()->assertJsonPath('user.idrol', 8);

        $this->assertDatabaseHas('negocio', [
            'iduser' => $response->json('user.id'),
            'nombre' => 'Cafetería Luna',
            'telefono' => '5551234567',
            'rfc' => 'RIVA900101XXX',
            'codigo_postal' => '06000',
        ]);
    }

    public function test_client_registration_does_not_create_a_business(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
            'password' => 'secret123',
            'rol' => 'cliente',
        ]);

        $response->assertCreated()->assertJsonPath('user.idrol', 9);
        $this->assertDatabaseCount('negocio', 0);
    }

    public function test_product_listing_only_returns_products_for_the_authenticated_business(): void
    {
        $owner = $this->createBusinessOwner('owner@example.com', 'Negocio propio');
        $otherOwner = $this->createBusinessOwner('other@example.com', 'Otro negocio');
        $brand = Marca::create(['nombre' => 'Marca de prueba']);

        $ownProduct = $this->createProduct($owner, $brand, 'Producto propio');
        $this->createProduct($otherOwner, $brand, 'Producto ajeno');

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/productos')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $ownProduct->id);
    }

    public function test_product_creation_uses_the_authenticated_business_id(): void
    {
        $owner = $this->createBusinessOwner('creator@example.com', 'Negocio creador');
        $otherOwner = $this->createBusinessOwner('target@example.com', 'Negocio ajeno');
        $brand = Marca::create(['nombre' => 'Marca de prueba']);

        $this->actingAs($owner, 'sanctum')
            ->postJson('/api/productos', [
                'nombre' => 'Producto nuevo',
                'idmarca' => $brand->id,
                'tipoProducto' => 'General',
                'precio' => 25.50,
                'idnegocio' => $otherOwner->negocio->id,
            ])
            ->assertCreated()
            ->assertJsonPath('idnegocio', $owner->negocio->id);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto nuevo',
            'idnegocio' => $owner->negocio->id,
        ]);
    }

    private function createBusinessOwner(string $email, string $businessName): User
    {
        $user = User::create([
            'name' => 'Business Owner',
            'email' => $email,
            'password' => Hash::make('secret123'),
            'idrol' => 8,
        ]);

        $user->negocio = Negocio::create([
            'iduser' => $user->id,
            'nombre' => $businessName,
        ]);

        return $user;
    }

    private function createProduct(User $owner, Marca $brand, string $name): Producto
    {
        return Producto::create([
            'nombre' => $name,
            'precio' => 10,
            'idnegocio' => $owner->negocio->id,
            'idmarca' => $brand->id,
            'tipoProducto' => 'General',
            'status' => 1,
        ]);
    }
}
