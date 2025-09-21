<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Invoice;
use Illuminate\Console\Command;

class UpdateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-invoice';

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
        //
        $orders = Invoice::all();
        $this->info('Updating invoices');
        $this->progressBar = $this->output->createProgressBar(count($orders));
        foreach ($orders as $order) {
            $company = Company::find(1);
            $order->company = json_encode($company);
            $order->save();
            $this->progressBar->advance();
        }
        $this->progressBar->finish();
    }
}
