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

use App\Models\User;

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.achievements.index', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.achievement.index');
    $response->assertViewHas('route');
    $response->assertViewHas('user', $user);
    $response->assertViewHas('achievements');
    $response->assertViewHas('pending');
});
