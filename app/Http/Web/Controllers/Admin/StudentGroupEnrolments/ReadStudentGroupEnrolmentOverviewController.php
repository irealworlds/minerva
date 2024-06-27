<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroupEnrolments;

use App\Core\Models\StudentGroupEnrolment;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\StudentEnrolmentDetailsViewModel;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadStudentGroupEnrolmentOverviewController extends
    Controller
{
    public function __construct(private ResponseFactory $_inertia)
    {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Admin/StudentEnrolments/{enrolment}/Overview',
            name: 'admin.studentGroupEnrolments.read.overview',
        ),
    ]
    public function __invoke(StudentGroupEnrolment $enrolment): InertiaResponse
    {
        return $this->_inertia->render('Admin/StudentEnrolments/ReadOverview', [
            'enrolment' => StudentEnrolmentDetailsViewModel::fromModel(
                $enrolment,
            ),
        ]);
    }
}
