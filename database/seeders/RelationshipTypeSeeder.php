<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RelationshipType;

class RelationshipTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Parella',
                'slug' => 'partner',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Familiar',
                'slug' => 'family',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Pare/Mare',
                'slug' => 'parent',
                'is_symmetric' => false,
                'reverse_name' => 'Fill/a',
            ],
            [
                'name' => 'Germà/na',
                'slug' => 'sibling',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Amic/ga',
                'slug' => 'friend',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Company de feina',
                'slug' => 'coworker',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Veí/na',
                'slug' => 'neighbor',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
            [
                'name' => 'Ex-parella',
                'slug' => 'ex-partner',
                'is_symmetric' => true,
                'reverse_name' => null,
            ],
        ];

        foreach ($types as $type) {
            RelationshipType::create($type);
        }
    }
}
