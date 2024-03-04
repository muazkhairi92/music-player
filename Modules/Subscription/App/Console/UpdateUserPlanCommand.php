<?php

namespace Modules\Subscription\App\Console;

use Illuminate\Console\Command;
use Modules\Subscription\App\Jobs\UpdateUserPlanJob;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateUserPlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscription:update-user-plan';

    /**
     * The console command description.
     */
    protected $description = 'Auto update user plan based on end date';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new UpdateUserPlanJob);
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
