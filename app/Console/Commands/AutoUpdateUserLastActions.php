<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Exception;

class AutoUpdateUserLastActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:update_user_last_actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user last actions in batches';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $key = config('cache.prefix').':user-last-actions:batch';
        $userIdCount = Redis::command('LLEN', [$key]);
        $userIds = Redis::command('LPOP', [$key, $userIdCount]);

        if ($userIds !== false) {
            User::whereIntegerInRaw('id', $userIds)->update([
                'last_action' => now(),
            ]);
        }

        $this->comment('Automated upsert histories command complete');
    }
}
