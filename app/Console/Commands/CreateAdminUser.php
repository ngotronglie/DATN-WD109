<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            $role = Role::create(['name' => 'admin']);
        }
        $user = User::where('email', 'admin@example.com')->first();
        if ($user) {
            $this->info('Admin user already exists!');
            return 0;
        }
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role_id' => $role->id,
            'address' => 'Admin Address',
            'phone_number' => '0123456789',
            'avatar' => 'default-avatar.png',
            'date_of_birth' => '1990-01-01',
        ]);
        $this->info('Admin user created: admin@example.com / 123456');
        return 0;
    }
}
