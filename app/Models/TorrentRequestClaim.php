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

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TorrentRequestClaim.
 *
 * @property int                             $id
 * @property int                             $request_id
 * @property int                             $user_id
 * @property int                             $anon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TorrentRequestClaim extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'request_claims';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Belongs To A User.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<TorrentRequest, self>
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(TorrentRequest::class);
    }
}
