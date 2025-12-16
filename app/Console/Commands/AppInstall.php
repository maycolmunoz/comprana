<?php

namespace App\Console\Commands;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class AppInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install setup for Comprana';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Installation...');

        $this->runMigrations();

        $this->storageLink();

        $this->runSeeder();

        $this->generateRolesAndPermissions();

        $this->createAdminUser();

        $this->info('🎉 Super panel installation completed successfully!');
    }

    /**
     * Run database migrations.
     */
    protected function runMigrations(): void
    {
        $this->info('⚙️ Running database migrations...');

        Artisan::call('migrate', [], $this->getOutput());

        $this->info('✅ Migrations completed successfully.');
    }

    /**
     * Run database seeders.
     */
    protected function runSeeder()
    {
        if (! $this->confirm('Do you want to fill the system with test data?')) {
            $this->info('❌ Seeders cancelled.');

            return;
        }

        $this->info('⚙️ Running database seeders...');
        Artisan::call('db:seed', [], $this->getOutput());
        $this->info('✅ Seeders completed successfully.');
    }

    /**
     * Create storage directory.
     */
    private function storageLink()
    {
        if (file_exists(public_path('storage'))) {
            return;
        }

        $this->info('🔗 Linking storage directory...');

        Artisan::call('storage:link', [], $this->getOutput());

        $this->info('✅ Storage directory linked successfully.');
    }

    /**
     * Generate roles and permissions using Filament Shield.
     */
    protected function generateRolesAndPermissions(): void
    {
        $this->info('🛡 Generating roles and permissions...');

        $adminRole = Role::firstOrCreate(['name' => $this->getAdminRoleName()]);

        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'permissions',
            '--panel' => 'admin',
        ], $this->getOutput());

        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        $this->info('✅ Roles and permissions generated and assigned successfully.');
    }

    /**
     * Create the initial Admin user with the Super Admin role.
     */
    protected function createAdminUser(): void
    {
        $this->info('👤 Creating an Admin user...');

        $userModel = app(Utils::getAuthProviderFQCN());

        $adminData = [
            'name' => text(
                'Name',
                default: 'Example',
                required: true
            ),
            'email' => text(
                'Email address',
                default: 'admin@example.com',
                required: true,
                validate: fn ($email) => $this->validateAdminEmail($email, $userModel)
            ),
            'password' => Hash::make(
                password(
                    'Password',
                    required: true,
                    validate: fn ($value) => $this->validateAdminPassword($value)
                )
            ),
            'resource_permission' => 'global',
        ];

        $adminUser = $userModel::updateOrCreate(['email' => $adminData['email']], $adminData);

        $adminRoleName = $this->getAdminRoleName();

        if (! $adminUser->hasRole($adminRoleName)) {
            $adminUser->assignRole($adminRoleName);
        }

        $this->info("✅ Admin user '{$adminUser->name}' created and assigned the '{$this->getAdminRoleName()}' role successfully.");
    }

    /**
     * Retrieve the Super Admin role name from the configuration.
     */
    protected function getAdminRoleName(): string
    {
        return Utils::getPanelUserRoleName();
    }

    /**
     * Validate the provided admin email.
     */
    protected function validateAdminEmail(string $email, Model $userModel): ?string
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'The email address must be valid.';
        }

        if ($userModel::where('email', $email)->exists()) {
            return 'A user with this email address already exists.';
        }

        return null;
    }

    /**
     * Validate the provided admin password.
     */
    protected function validateAdminPassword(string $password): ?string
    {
        return strlen($password) >= 8 ? null : 'The password must be at least 8 characters long.';
    }
}
