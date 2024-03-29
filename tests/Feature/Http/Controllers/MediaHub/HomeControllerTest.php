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

    $response = $this->actingAs($user)->get(route('mediahub.index'));
    $response->assertOk();
    $response->assertViewIs('mediahub.index');
    $response->assertViewHas('tv');
    $response->assertViewHas('movies');
    $response->assertViewHas('movieCategoryIds');
    $response->assertViewHas('collections');
    $response->assertViewHas('persons');
    $response->assertViewHas('genres');
    $response->assertViewHas('networks');
    $response->assertViewHas('companies');
});
