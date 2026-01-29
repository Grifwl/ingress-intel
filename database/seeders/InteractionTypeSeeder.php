<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InteractionType;

class InteractionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Anomalia',
                'slug' => 'anomaly',
                'description' => 'Competició oficial organitzada per Niantic',
                'icon' => 'trophy',
                'color' => '#FFD700',
                'is_positive' => true,
            ],
            [
                'name' => 'Operació Conjunta',
                'slug' => 'joint-operation',
                'description' => 'Operació coordinada amb altres agents',
                'icon' => 'users',
                'color' => '#00A8E8',
                'is_positive' => true,
            ],
            [
                'name' => 'Conflicte',
                'slug' => 'conflict',
                'description' => 'Situació conflictiva o enfrontament',
                'icon' => 'alert-triangle',
                'color' => '#FF4444',
                'is_positive' => false,
            ],
            [
                'name' => 'Spoof Detectat',
                'slug' => 'spoof-detected',
                'description' => 'Detecció d\'ús de GPS falsejat',
                'icon' => 'map-pin-off',
                'color' => '#FF0000',
                'is_positive' => false,
            ],
            [
                'name' => 'First Saturday',
                'slug' => 'first-saturday',
                'description' => 'Esdeveniment mensual First Saturday',
                'icon' => 'calendar',
                'color' => '#9C27B0',
                'is_positive' => true,
            ],
            [
                'name' => 'Trobada Social',
                'slug' => 'social-meetup',
                'description' => 'Trobada informal amb altres agents',
                'icon' => 'coffee',
                'color' => '#795548',
                'is_positive' => true,
            ],
            [
                'name' => 'Comportament Sospitós',
                'slug' => 'suspicious-behavior',
                'description' => 'Comportament que cal investigar',
                'icon' => 'eye',
                'color' => '#FF9800',
                'is_positive' => false,
            ],
        ];

        foreach ($types as $type) {
            InteractionType::create($type);
        }
    }
}
