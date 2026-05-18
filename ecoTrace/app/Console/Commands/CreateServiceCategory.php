<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateServiceCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-service-category {name} {description?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new e-waste service category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $description = $this->argument('description') ?? "Recycling services for {$name}";

        $slug = Str::slug($name);

        if (Category::where('slug', $slug)->exists()) {
            $this->error("Category with slug '{$slug}' already exists!");
            return Command::FAILURE;
        }

        $category = Category::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        $this->info("Category '{$category->name}' created successfully with slug '{$category->slug}'!");
        return Command::SUCCESS;
    }
}
