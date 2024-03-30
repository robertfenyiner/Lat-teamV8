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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreMassActionRequest;
use App\Jobs\ProcessMassPM;
use App\Models\Group;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Exception;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\MassActionControllerTest
 */
class MassActionController extends Controller
{
    /**
     * Mass PM Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.masspm.index');
    }

    /**
     * Send The Mass PM.
     *
     * @throws Exception
     */
    public function store(StoreMassActionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $users = User::whereNotIn(
            'group_id',
            Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned'])
        )
            ->pluck('id');

        foreach ($users as $userId) {
            ProcessMassPM::dispatch(User::SYSTEM_USER_ID, $userId, $request->subject, $request->message);
        }

        return to_route('staff.mass-pm.create')
            ->withSuccess('MassPM Sent');
    }

    /**
     * Mass Validate Unvalidated Users.
     *
     * @throws Exception
     */
    public function update(): \Illuminate\Http\RedirectResponse
    {
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $memberGroup = cache()->rememberForever('member_group', fn () => Group::where('slug', '=', 'user')->pluck('id'));

        foreach (User::where('group_id', '=', $validatingGroup[0])->get() as $user) {
            $user->update([
                'group_id'          => $memberGroup[0],
                'active'            => 1,
                'can_upload'        => 1,
                'can_download'      => 1,
                'can_request'       => 1,
                'can_comment'       => 1,
                'can_invite'        => 1,
                'email_verified_at' => now(),
            ]);

            Unit3dAnnounce::addUser($user);
        }

        return to_route('staff.dashboard.index')
            ->withSuccess('Unvalidated Accounts Are Now Validated');
    }
}
