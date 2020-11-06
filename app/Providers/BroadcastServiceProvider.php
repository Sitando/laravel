<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\{ServiceProvider, Facades\Broadcast};

class BroadcastServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		Broadcast::routes();

		require base_path('routes/channels.php');
	}
}
