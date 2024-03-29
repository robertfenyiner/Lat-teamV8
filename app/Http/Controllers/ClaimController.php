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

namespace App\Http\Controllers;

use App\Http\Requests\StoreTorrentRequestClaimRequest;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestClaim;
use App\Notifications\NewRequestClaim;
use App\Notifications\NewRequestUnclaim;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RequestControllerTest
 */
class ClaimController extends Controller
{
    /**
     * Claim A Torrent Request.
     */
    public function store(StoreTorrentRequestClaimRequest $request, TorrentRequest $torrentRequest): \Illuminate\Http\RedirectResponse
    {
        if ($torrentRequest->claimed !== null) {
            return to_route('requests.show', ['torrentRequest' => $torrentRequest])
                ->withErrors(trans('request.already-claimed'));
        }

        $claim = $torrentRequest->claim()->create(['user_id' => $request->user()->id] + $request->validated());

        $torrentRequest->update([
            'claimed' => true,
        ]);

        $requester = $torrentRequest->user;

        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_claim')) {
            $requester->notify(new NewRequestClaim($claim));
        }

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(trans('request.claimed-success'));
    }

    /**
     * Unclaim A Torrent Request.
     *
     * @throws Exception
     */
    public function destroy(Request $request, TorrentRequest $torrentRequest, TorrentRequestClaim $claim): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo || $request->user()->id == $claim->user_id, 403);

        $claim->delete();

        $torrentRequest->update([
            'claimed' => null,
        ]);

        $claimer = $claim->anon ? 'Anonymous' : $request->user()->username;
        $requester = $torrentRequest->user;

        if ($requester->acceptsNotification($request->user(), $requester, 'request', 'show_request_unclaim')) {
            $requester->notify(new NewRequestUnclaim('torrent', $claimer, $torrentRequest));
        }

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(trans('request.unclaimed-success'));
    }
}
