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

test('user factory returns correct values when created', function (): void {
    $user = User::factory()->create();

    $user->makeVisible([
        'email',
        'password',
        'passkey',
        'rsskey',
        'remember_token',
        'api_token',
    ]);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->toHaveKeys([
            'username',
            'email',
            'email_verified_at',
            'password',
            'passkey',
            'group_id',
            'active',
            'uploaded',
            'downloaded',
            'image',
            'title',
            'about',
            'signature',
            'fl_tokens',
            'seedbonus',
            'invites',
            'hitandruns',
            'rsskey',
            'chatroom_id',
            'censor',
            'chat_hidden',
            'hidden',
            'style',
            'torrent_layout',
            'torrent_filters',
            'custom_css',
            'read_rules',
            'can_chat',
            'can_comment',
            'can_download',
            'can_request',
            'can_invite',
            'can_upload',
            'show_poster',
            'peer_hidden',
            'private_profile',
            'block_notifications',
            'stat_hidden',
            'remember_token',
            'api_token',
            'last_login',
            'last_action',
            //    'disabled_at',
            //    'deleted_by',
            'locale',
            'chat_status_id',
        ]);
});
