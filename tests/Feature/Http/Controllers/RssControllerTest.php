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

use App\Models\Category;
use App\Models\Genre;
use App\Models\Resolution;
use App\Models\Rss;
use App\Models\Torrent;
use App\Models\Type;
use App\Models\User;

test('create returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $categories = Category::factory()->times(3)->create();
    $types = Type::factory()->times(3)->create();
    $resolutions = Resolution::factory()->times(3)->create();
    $genres = Genre::factory()->times(3)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('rss.create'));

    $response->assertOk();
    $response->assertViewIs('rss.create');
    $response->assertViewHas('categories', $categories);
    $response->assertViewHas('types', $types);
    $response->assertViewHas('resolutions', $resolutions);
    $response->assertViewHas('genres', $genres);
    $response->assertViewHas('user', $user);

    // TODO: perform additional assertions
});

test('destroy returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('rss.destroy', ['id' => $rss->id]));

    $response->assertOk();
    $this->assertModelMissing($rss);

    // TODO: perform additional assertions
});

test('edit returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $categories = Category::factory()->times(3)->create();
    $types = Type::factory()->times(3)->create();
    $resolutions = Resolution::factory()->times(3)->create();
    $genres = Genre::factory()->times(3)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('rss.edit', ['id' => $rss->id]));

    $response->assertOk();
    $response->assertViewIs('rss.edit');
    $response->assertViewHas('categories', $categories);
    $response->assertViewHas('types', $types);
    $response->assertViewHas('resolutions', $resolutions);
    $response->assertViewHas('genres', $genres);
    $response->assertViewHas('user', $user);
    $response->assertViewHas('rss', $rss);

    // TODO: perform additional assertions
});

test('edit aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $categories = Category::factory()->times(3)->create();
    $types = Type::factory()->times(3)->create();
    $resolutions = Resolution::factory()->times(3)->create();
    $genres = Genre::factory()->times(3)->create();
    $user = User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($user)->get(route('rss.edit', ['id' => $rss->id]));

    $response->assertForbidden();
});

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rsses = Rss::factory()->times(3)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('rss.index'));

    $response->assertOk();
    $response->assertViewIs('rss.index');
    $response->assertViewHas('hash');
    $response->assertViewHas('public_rss');
    $response->assertViewHas('private_rss');
    $response->assertViewHas('user', $user);

    // TODO: perform additional assertions
});

test('show returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $torrents = Torrent::factory()->times(3)->create();

    $response = $this->get(route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => $rss->rsskey]));

    $response->assertOk();

    // TODO: perform additional assertions
});

test('show aborts with a 404', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $torrents = Torrent::factory()->times(3)->create();

    // TODO: perform additional setup to trigger `abort_if(404)`...

    $response = $this->get(route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => $rss->rsskey]));

    $response->assertNotFound();
});

test('store returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('rss.store'), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('update returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $rss = Rss::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('rss.update', ['id' => $rss->id]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

// test cases...
