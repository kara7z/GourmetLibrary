<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Chef Admin',
            'email' => 'admin@gourmet.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular user
        User::create([
            'name' => 'Gourmand User',
            'email' => 'user@gourmet.com',
            'password' => Hash::make('password'),
            'role' => 'gourmand',
        ]);

        $categories = [
            ['name' => 'Pâtisserie Française', 'description' => 'Recettes de pâtisserie française traditionnelle'],
            ['name' => 'Sans Gluten', 'description' => 'Recettes sans gluten pour tous'],
            ['name' => 'Cuisine du Monde', 'description' => 'Voyages culinaires autour du globe'],
            ['name' => 'Végétarien', 'description' => 'Cuisine végétarienne et végane'],
            ['name' => 'Cuisine Méditerranéenne', 'description' => 'Saveurs du bassin méditerranéen'],
        ];

        foreach ($categories as $cat) {
            $category = Category::create($cat);

            Book::create([
                'title' => 'Le Grand Livre de ' . $cat['name'],
                'author' => 'Chef Dupont',
                'description' => 'Un ouvrage de référence sur ' . $cat['name'],
                'publication_date' => '2023-01-15',
                'category_id' => $category->id,
                'is_new_arrival' => false,
                'borrow_count' => rand(0, 50),
                'consultation_count' => rand(0, 100),
                'damaged_quantity' => rand(0, 3),
            ]);

            Book::create([
                'title' => 'Nouvelles Recettes de ' . $cat['name'],
                'author' => 'Chef Martin',
                'description' => 'Les dernières tendances en ' . $cat['name'],
                'publication_date' => '2024-06-01',
                'category_id' => $category->id,
                'is_new_arrival' => true,
                'borrow_count' => rand(0, 30),
                'consultation_count' => rand(0, 60),
                'damaged_quantity' => 0,
            ]);
        }
    }
}
