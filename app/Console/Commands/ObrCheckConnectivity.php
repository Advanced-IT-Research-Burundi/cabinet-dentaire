<?php

namespace App\Console\Commands;

use App\Http\Controllers\SendInvoiceToOBR;
use Illuminate\Console\Command;

class ObrCheckConnectivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'obr:check-connectivity';

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
        $this->info('Checking connectivity...');
        $obr = new SendInvoiceToOBR();
        $token = $obr->getToken();
        if ($token) {

            $this->info('Connected successfully!');
            $this->info($token);
        } else {
            $this->error('Failed to connect to OBR!');
        }
    }
}
