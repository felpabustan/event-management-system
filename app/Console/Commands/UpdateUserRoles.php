<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing users to have default admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user roles...');
        
        // Update all users without roles to be super_admins (since they were existing admins)
        $usersUpdated = User::whereNull('role')->update(['role' => 'super_admin']);
        
        $this->info("Updated {$usersUpdated} users to super_admin role.");
        
        return Command::SUCCESS;
    }
}
