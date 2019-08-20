<?php
namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DeleteTenant extends Command
{
    protected $signature = 'tenant:delete {name}';
    protected $description = 'Deletes a tenant of the provided name. Only available on the local environment e.g. php artisan tenant:delete boise';
    
    public function handle()
    {
        // because this is a destructive command, we'll only allow to run this command
        // if you are on the local environment
        if (!app()->isLocal()) {
            $this->error('This command is only avilable on the local environment.');
            return;
        }
        
        $name = $this->argument('name');
        
        $this->deleteTenant($name);
    }
    
    private function deleteTenant($name)
    {
        $baseUrl = config('app.url_base');
        $fqdn = "{$name}.{$baseUrl}";

        if ($hostname = Hostname::where('fqdn', $fqdn)->withTrashed()->firstOrFail()) {
            $website = $hostname->website()->withTrashed()->first();
            
            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);
            
            $this->info("Tenant {$name} successfully deleted.");
        }
    }
}