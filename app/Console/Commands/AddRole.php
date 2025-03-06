<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AddRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:add {name : The name of the role} {--permissions=* : The permissions to assign to the role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new role with optional permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roleName = $this->argument('name');

        try {
            // Check if role already exists
            if (Role::where('name', $roleName)->exists()) {
                $this->error("Role '{$roleName}' already exists!");
                return 1;
            }

            // Create the role
            $role = Role::create(['name' => $roleName]);
            

            $this->info("Role '{$roleName}' created successfully!");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error creating role: {$e->getMessage()}");
            return 1;
        }
    }
} 