<?php
namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UserInvite;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {email}';
    protected $description = 'Creates a tenant with the provided name and email address e.g. php artisan tenant:create boise boise@example.com';
    
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        if ($this->tenantExists($name)) {
            $this->error("A tenant with name '{$name}' already exists.");
            return;
        }
        
        $hostname = $this->registerTenant($name);
        app(Environment::class)->hostname($hostname);
        
        // we'll create a random secure password for our to-be admin
        $admin = $this->addAdmin($name, $email);
        $admin->notify(new UserInvite($hostname));

        $this->info("Tenant '{$admin->name}' is created and is now accessible at {$hostname->fqdn}");
        $this->info("Admin {$email} has been invited!");
    }
    
    private function tenantExists($name)
    {
        return Hostname::where('name', $name)->exists();
    }
    
    private function registerTenant($name)
    {        
        $baseUrl = config('app.url_base');
        $fqdn = "{$name}.{$baseUrl}";
        
        // associate the customer with a website
        $website = new Website;
        app(WebsiteRepository::class)->create($website);
        
        // associate the website with a hostname
        $hostname = new Hostname;
        $hostname->name = $name;
        $hostname->fqdn = $fqdn;
        app(HostnameRepository::class)->attach($hostname, $website);
        
        $this->call('tenant:module-migrate', [
            '--website_id' => $website->id,
            '--seed' => true
        ]);

        return $hostname;
    }

    private function addAdmin($name, $email)
    {
        $admin = User::create(['name' => ucfirst($name).' Admin', 'email' => $email, 'password' => Hash::make(str_random())]);
        $admin->guard_name = 'web';
        $admin->assignRole('admin');
        
        return $admin;
    }
}