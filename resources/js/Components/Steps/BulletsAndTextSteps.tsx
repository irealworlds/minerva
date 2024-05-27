import { CheckCircleIcon } from '@heroicons/react/20/solid';
import { useMemo } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface Step {
  id: number;
  name: string;
}

export default function BulletsAndTextSteps({
  className,
  steps,
  activeStepId,
  onActiveStepChange,
  disabled,
}: {
  steps: Step[];
  activeStepId: Step['id'];
  className?: string;
  onActiveStepChange: (newId: Step['id']) => void;
  disabled?: boolean;
}) {
  const stepsWithStatus = useMemo<
    (Step & {
      status: 'forthcoming' | 'current' | 'completed';
    })[]
  >(
    () =>
      steps.map(step => ({
        ...step,
        status:
          step.id === activeStepId
            ? 'current'
            : step.id < activeStepId
              ? 'completed'
              : 'forthcoming',
      })),
    [steps, activeStepId]
  );

  return (
    <div className={className}>
      <nav className="flex justify-center" aria-label="Progress">
        <ol role="list" className="space-y-6">
          {stepsWithStatus.map(step => (
            <li key={step.name}>
              {step.status === 'completed' ? (
                <button
                  disabled={disabled}
                  type="button"
                  onClick={() => {
                    onActiveStepChange(step.id);
                  }}
                  className="group">
                  <span className="flex items-start">
                    <span className="relative flex h-5 w-5 flex-shrink-0 items-center justify-center">
                      <CheckCircleIcon
                        className="h-full w-full text-indigo-600 group-hover:text-indigo-800"
                        aria-hidden="true"
                      />
                    </span>
                    <span className="ml-3 text-sm font-medium text-gray-500 group-hover:text-gray-900">
                      {step.name}
                    </span>
                  </span>
                </button>
              ) : step.status === 'current' ? (
                <button
                  disabled={disabled}
                  onClick={() => {
                    onActiveStepChange(step.id);
                  }}
                  className="flex items-start"
                  aria-current="step">
                  <span
                    className="relative flex h-5 w-5 flex-shrink-0 items-center justify-center"
                    aria-hidden="true">
                    <span className="absolute h-4 w-4 rounded-full bg-indigo-200" />
                    <span className="relative block h-2 w-2 rounded-full bg-indigo-600" />
                  </span>
                  <span className="ml-3 text-sm font-medium text-indigo-600">
                    {step.name}
                  </span>
                </button>
              ) : (
                <button
                  disabled={disabled}
                  onClick={() => {
                    onActiveStepChange(step.id);
                  }}
                  className="group">
                  <div className="flex items-start">
                    <div
                      className="relative flex h-5 w-5 flex-shrink-0 items-center justify-center"
                      aria-hidden="true">
                      <div
                        className={combineClassNames(
                          'h-2 w-2 rounded-full bg-gray-300',
                          disabled ? '' : 'group-hover:bg-gray-400'
                        )}
                      />
                    </div>
                    <p
                      className={combineClassNames(
                        'ml-3 text-sm font-medium text-gray-500',
                        disabled ? '' : 'group-hover:text-gray-900'
                      )}>
                      {step.name}
                    </p>
                  </div>
                </button>
              )}
            </li>
          ))}
        </ol>
      </nav>
    </div>
  );
}
