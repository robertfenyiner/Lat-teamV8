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
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('automatic_torrent_freeleeches', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('position');
            $table->string('name_regex')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->unsignedBigInteger('resolution_id')->nullable();
            $table->integer('freeleech_percentage');
            $table->timestamps();
        });
    }
};
