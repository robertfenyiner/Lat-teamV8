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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            $table->unsignedInteger('reported_user')->nullable()->default(null)->change();
            $table->unsignedInteger('torrent_id')->nullable()->default(null)->change();
            $table->unsignedInteger('request_id')->nullable()->default(null)->change();
        });

        DB::table('reports')->where('reported_user', '=', 0)->update([
            'reported_user' => null,
            'updated_at'    => DB::raw('updated_at'),
        ]);

        DB::table('reports')->where('torrent_id', '=', 0)->update([
            'torrent_id' => null,
            'updated_at' => DB::raw('updated_at'),
        ]);

        DB::table('reports')->where('request_id', '=', 0)->update([
            'request_id' => null,
            'updated_at' => DB::raw('updated_at'),
        ]);
    }
};
