<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;

class SpaceSeeder extends Seeder
{
    public function run()
    {
        Space::create([
            'name' => 'Sala de Conferencias A',
            'description' => 'Sala grande con capacidad para 50 personas',
            'capacity' => 50,
            'type' => 'room',
        ]);

        Space::create([
            'name' => 'Sala de Reuniones B',
            'description' => 'Sala mediana con capacidad para 20 personas',
            'capacity' => 20,
            'type' => 'meeting_room',
        ]);

        Space::create([
            'name' => 'Oficina Privada C',
            'description' => 'Oficina pequeña para 5 personas',
            'capacity' => 5,
            'type' => 'desk',
        ]);
    }
}