<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Exception;

class EmailBlacklistUpdater
{
    public static function update(): bool|int
    {
        $url = config('email-blacklist.source');

        if ($url === null) {
            return false;
        }

        // Define parameters for the cache
        $key = config('email-blacklist.cache-key');
        $duration = Carbon::now()->addMonth();

        if (cache()->get($key) === null) {
            try {
                $domains = Http::get($url)->json();
            } catch (Exception) {
                $domains = [];
            }
        } else {
            $domains = Http::get($url)->json();
        }

        $count = is_countable($domains) ? \count($domains) : 0;

        // Store blacklisted domains
        cache()->put($key, $domains, $duration);

        return $count;
    }
}
