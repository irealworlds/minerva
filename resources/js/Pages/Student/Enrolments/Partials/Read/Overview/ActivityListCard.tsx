import { StudentEnrolmentActivityType } from '@/types/enums/student-enrolment-activity-type.enum';
import NewGrade from '@/Pages/Student/Enrolments/Partials/Read/Overview/Activities/NewGrade';
import Enrolment from '@/Pages/Student/Enrolments/Partials/Read/Overview/Activities/Enrolment';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { StudentEnrolmentActivityItemViewModel } from '@/types/view-models/student/student-enrolment-activity-item.view-model';

interface ActivityListCardProps {
    className?: string;
    activities: StudentEnrolmentActivityItemViewModel[];
}

export default function ActivityListCard({
    className,
    activities,
}: ActivityListCardProps) {
    return (
        <div
            className={combineClassNames(
                className,
                'flow-root bg-white rounded-lg shadow'
            )}>
            <div className="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 className="text-base font-semibold leading-6 text-gray-900">
                    Latest activity
                </h3>
                <p className="mt-1 text-sm text-gray-500">
                    The latest activities you have been involved in in this
                    student group.
                </p>
            </div>
            <ul role="list" className="-mb-8 px-4 py-5 sm:px-6">
                {activities.map((activityItem, activityItemIdx) => (
                    <li key={activityItemIdx}>
                        <div className="relative pb-8">
                            {activityItemIdx !== activities.length - 1 ? (
                                <span
                                    className="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"
                                />
                            ) : null}
                            <div className="relative flex items-start space-x-3">
                                {activityItem.type ===
                                StudentEnrolmentActivityType.NewGrade ? (
                                    <NewGrade
                                        awardedBy={
                                            activityItem.properties.awardedBy
                                        }
                                        awardedPoints={
                                            activityItem.properties
                                                .awardedPoints
                                        }
                                        maximumPoints={
                                            activityItem.properties
                                                .maximumPoints
                                        }
                                        awardedAt={activityItem.date}
                                        notes={activityItem.properties.notes}
                                    />
                                ) : (
                                    <Enrolment enroledAt={activityItem.date} />
                                )}
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
