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

use App\Http\Controllers\Staff\ReportController;
use App\Http\Requests\Staff\UpdateReportRequest;
use App\Models\Report;
use App\Models\User;

test('index returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $reports = Report::factory()->times(3)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.reports.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.report.index');
    $response->assertViewHas('reports', $reports);

    // TODO: perform additional assertions
});

test('show returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $report = Report::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.reports.show', [$report]));

    $response->assertOk();
    $response->assertViewIs('Staff.report.show');
    $response->assertViewHas('report', $report);
    $response->assertViewHas('urls');

    // TODO: perform additional assertions
});

test('update validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        ReportController::class,
        'update',
        UpdateReportRequest::class
    );
});

test('update returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $report = Report::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('staff.reports.update', [$report]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

// test cases...
