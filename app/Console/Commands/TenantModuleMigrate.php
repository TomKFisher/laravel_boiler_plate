<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Database\Connection;
use Module;

class TenantModuleMigrate extends Command
{
    use ConfirmableTrait;
    
    /**
     *
     * @var Hyn\Tenancy\Database\Connection
     */
    private $connection;
    
    /**
     *
     * @var string
     */
    private $database;

    /**
     * String for path to modules migration folder. Please note, you will need to create the tenant folder after generating your module using the module:make command
     * 
     * @var string
     */
    private $module_tenant_migrations_path = 'database/Migrations/tenant';

    /**
     * String to prepend to the start of the Modules Database Seeder class name, default is {module_name}\Database\Seeders\
     * 
     * @var string
     */
    private $db_seeder_prefix = '\Database\Seeders\\';

    /**
     * String to append to the end of the Modules Database Seeder, default is always {module_name}DatabaseSeeder
     * 
     * @var string
     */
    private $db_seeder_suffix = 'DatabaseSeeder';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:module-migrate {--website_id=} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will loop through all the Modules, detect their tenant migrations and fire them into the tenants DBs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection = app(Connection::class);
        $this->database = $this->connection->tenantName();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }
        
        $website_id = $this->option('website_id');
        $websites = Website::where(function($query) use ($website_id){
            if(!empty($website_id))
                $query->where('id', $website_id);
        })->get();

        $modules = collect(Module::allEnabled());

        foreach($websites as $website){
            $this->comment('Migrating into: '.$website->uuid);
            foreach($modules as $module){
                $this->info('Migrating Module: '.$module);
                $this->call('tenancy:migrate', [
                    '--database' => $this->database,
                    '--website_id' => [$website->id],
                    '--path' => Module::getModulePath($module).$this->module_tenant_migrations_path,
                    '--realpath' => Module::getModulePath($module).$this->module_tenant_migrations_path,
                    '--force' => 1,
                ]);

                if ($this->needsSeeding()) {
                    $seeder_class = str_replace(DIRECTORY_SEPARATOR, '\\\\', 'Modules\\'.$module.$this->db_seeder_prefix.$module.$this->db_seeder_suffix);
                    if(class_exists($seeder_class)){
                        $this->info('Seeding Module: '.$module);
                        $this->call('tenancy:db:seed', [
                            '--database' => $this->database,
                            '--website_id' => [$website->id],
                            '--class' => $seeder_class,
                            '--force' => 1,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->option('seed');
    }
}
