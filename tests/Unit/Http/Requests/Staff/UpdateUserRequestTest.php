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

use App\Http\Requests\Staff\UpdateUserRequest;

beforeEach(function (): void {
    $this->subject = new UpdateUserRequest();
});

test('authorize', function (): void {
    $actual = $this->subject->authorize();

    expect($actual)->toBeTrue();
});

test('rules', function (): void {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'username' => [
            'required',
        ],
        'uploaded' => [
            'required',
            'integer',
        ],
        'downloaded' => [
            'required',
            'integer',
        ],
        'title' => [
            'nullable',
            'present',
            'string',
            'max:255',
        ],
        'about' => [
            'nullable',
            'present',
            'string',
            'max:16777216',
        ],
        'group_id' => [
            'required',
            'exists:groups,id',
        ],
        'seedbonus' => [
            'required',
            'decimal:0,2',
            'min:0',
        ],
        'invites' => [
            'required',
            'integer',
            'min:0',
        ],
        'fl_tokens' => [
            'required',
            'integer',
            'min:0',
        ],
    ], $actual);
});
