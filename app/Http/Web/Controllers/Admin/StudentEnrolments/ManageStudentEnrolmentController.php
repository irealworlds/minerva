<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentEnrolments;

use App\Core\Models\StudentGroupEnrolment;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\StudentEnrolmentDetailsViewModel;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageStudentEnrolmentController extends Controller
{
    public function __construct(private ResponseFactory $_inertia)
    {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Admin/StudentEnrolments/{enrolment}',
            name: 'admin.student_enrolments.manage',
        ),
    ]
    public function __invoke(StudentGroupEnrolment $enrolment): InertiaResponse
    {
        return $this->_inertia->render(
            'Admin/StudentEnrolments/ManageDetails',
            [
                'enrolment' => StudentEnrolmentDetailsViewModel::fromModel(
                    $enrolment,
                ),
            ],
        );
    }
}
