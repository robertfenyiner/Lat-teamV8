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

namespace Database\Seeders;

use App\Models\TicketPriority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketPrioritiesTableSeeder extends Seeder
{
    final public function run(): void
    {
        TicketPriority::upsert([
            [
                'name'     => 'Low',
                'position' => 0,
            ],
            [
                'name'     => 'Medium',
                'position' => 1,
            ],
            [
                'name'     => 'High',
                'position' => 2,
            ],
        ], ['id'], ['updated_at' => DB::raw('updated_at')]);
    }
}
