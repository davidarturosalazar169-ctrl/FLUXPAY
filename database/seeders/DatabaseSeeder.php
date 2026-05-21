<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // USERS

        // Clientes
        for ($i = 0; $i < 80; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('123456'),
                'idrol' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Vendedores
        for ($i = 0; $i < 40; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('123456'),
                'idrol' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $vendedores = DB::table('users')->where('idrol', 2)->pluck('id');
        $clientes   = DB::table('users')->where('idrol', 3)->pluck('id');

        // TIPO NEGOCIO
        DB::table('tipo_negocio')->insert([
            ['nombre'=>'Tienda','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'Servicio','created_at'=>now(),'updated_at'=>now()],
        ]);

        $tipos = DB::table('tipo_negocio')->pluck('id');

        // NEGOCIOS
        $negocios = [];

        foreach ($vendedores as $v) {
            $negocios[] = DB::table('negocio')->insertGetId([
                'iduser'=>$v,
                'nombre'=>$faker->company,
                'idtipo'=>$faker->randomElement($tipos),
                'rfc'=>strtoupper($faker->bothify('????######???')),
                'codigo_postal'=>$faker->postcode,
                'descripcion'=>$faker->sentence,
                'status'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // PLANES NEGOCIO
        $planes = [1, 2, 3];

        foreach ($negocios as $n) {
            DB::table('planesnegocio')->insert([
                'idplan' => $faker->randomElement($planes),
                'idnegocio' => $n,
                'dias' => $faker->randomElement([30, 60, 90, 180, 365]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // DIRECCION
        foreach ($negocios as $n) {
            DB::table('direccion')->insert([
                'idnegocio'=>$n,
                'numero_exterior'=>$faker->buildingNumber,
                'numero_interior'=>$faker->optional()->randomDigit,
                'estado'=>'Yucatán',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // CUENTA BANCARIA
        foreach ($negocios as $n) {
            DB::table('cuenta_bancaria')->insert([
                'idnegocio'=>$n,
                'banco'=>$faker->randomElement(['BBVA','Banorte','Santander']),
                'numero_cuenta'=>$faker->bankAccountNumber,
                'longitud'=>10,
                'clave'=>strtoupper($faker->bothify('CLV###??')),
                'fecha_registro'=>now(),
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // MARCAS
        $marcas = [];
        for ($i = 0; $i < 20; $i++) {
            $marcas[] = DB::table('marca')->insertGetId([
                'nombre'=>$faker->company,
                'descripcion'=>$faker->sentence,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // CATALOGO REAL
        $catalogo = [
            'iPhone 13','Samsung Galaxy S22','Xiaomi Redmi Note 12',
            'Laptop HP 15','Laptop Lenovo IdeaPad','MacBook Air M1',
            'Monitor LG 24 pulgadas','Teclado mecanico RGB',
            'Mouse Logitech','Disco SSD Kingston 1TB',
            'Smart TV Samsung 50 pulgadas','Control Xbox Series X',
            'Silla gamer ergonomica','Escritorio de oficina',
            'Audifonos Sony Bluetooth','Bocina JBL Charge',
            'Cafetera Oster','Licuadora Ninja',
            'Freidora de aire','Ventilador de torre',
            'Zapatillas Nike Air','Tenis Adidas Running',
            'Sudadera deportiva','Jeans slim fit',
            'Perfume Calvin Klein','Reloj Casio',
            'Mochila escolar','Maleta de viaje',
            'Libro de programacion','Agenda 2026'
        ];

        $adjetivos = ['Pro','Max','Plus','Ultra','2024'];

        // PRODUCTOS (100)
        for ($i = 0; $i < 100; $i++) {
            DB::table('productos')->insert([
                'nombre' => $catalogo[array_rand($catalogo)] . ' ' . $adjetivos[array_rand($adjetivos)],
                'precio' => rand(100, 5000),
                'idnegocio' => $negocios[array_rand($negocios)],
                'idmarca' => $marcas[array_rand($marcas)],
                'status' => 1,
                'fecha_registro' => now(),
                'fecha_actualizacion' => now(),
                'tipoProducto' => ['fisico','digital'][array_rand(['fisico','digital'])],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // MOVIMIENTOS (700)
        $movimientos = [];

        for ($i = 0; $i < 700; $i++) {
            $movimientos[] = DB::table('movimiento')->insertGetId([
                'idnegocio'=>$faker->randomElement($negocios),
                'iduser'=>$faker->randomElement($clientes),
                'monto_total'=>0,
                'comision'=>0,
                'metodo_pago'=>$faker->randomElement(['tarjeta','efectivo','transferencia']),
                'referencia_pago'=>strtoupper($faker->bothify('REF###??')),
                'fecha_movimiento'=>$faker->dateTimeThisYear(),
                'status'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // DETALLE MOVIMIENTOS (REAL)
        foreach ($movimientos as $mov) {
            $total = 0;

            for ($i = 0; $i < rand(3,6); $i++) {

                $producto = DB::table('productos')->inRandomOrder()->first();

                $cantidad = rand(1,4);
                $subtotal = $cantidad * $producto->precio;
                $total += $subtotal;

                DB::table('detalle_movimientos')->insert([
                    'idmovimiento'=>$mov,
                    'idproducto'=>$producto->id,
                    'cantidad'=>$cantidad,
                    'precio_unitario'=>$producto->precio,
                    'subtotal'=>$subtotal,
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
            }

            DB::table('movimiento')->where('id',$mov)->update([
                'monto_total'=>$total,
                'comision'=>$total * 0.05
            ]);
        }

        // QR (700)
        foreach ($movimientos as $mov) {
            DB::table('qr')->insert([
                'idnegocio'=>$faker->randomElement($negocios),
                'idmovimiento'=>$mov,
                'codigo'=>strtoupper($faker->uuid),
                'monto'=>$faker->randomFloat(2,50,500),
                'fecha_creacion'=>now(),
                'fecha_expiracion'=>now()->addDays(2),
                'status'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        // TARJETAS
        foreach ($clientes as $c) {
            DB::table('tarjeta_cliente')->insert([
                'user_id'=>$c,
                'brand'=>$faker->creditCardType,
                'last4'=>substr($faker->creditCardNumber,-4),
                'exp_month'=>rand(1,12),
                'exp_year'=>rand(2026,2032),
                'name'=>$faker->name,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}