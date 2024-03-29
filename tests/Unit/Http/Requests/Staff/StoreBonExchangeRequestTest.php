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

use App\Http\Requests\Staff\StoreBonExchangeRequest;

beforeEach(function (): void {
    $this->subject = new StoreBonExchangeRequest();
});

test('authorize', function (): void {
    $actual = $this->subject->authorize();

    expect($actual)->toBeTrue();
});

test('rules', function (): void {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'description' => [
            'required',
            'string',
        ],
        'value' => [
            'required',
            'numeric',
        ],
        'cost' => [
            'required',
            'numeric',
        ],
        'type' => [
            'required',
            'string',
            'in:upload,download,personal_freeleech,invite',
            'exclude',
        ],
    ], $actual);
});
