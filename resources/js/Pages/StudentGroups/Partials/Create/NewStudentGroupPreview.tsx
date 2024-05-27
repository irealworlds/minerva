import PrimaryButton from '@/Components/PrimaryButton';
import { GroupCreationFormData } from '@/Pages/StudentGroups/Create';
import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';
import React from 'react';
import {
  BuildingLibraryIcon,
  UserGroupIcon,
} from '@heroicons/react/24/outline';
import { combineClassNames } from '@/utils/combine-class-names.function';
import SecondaryButton from '@/Components/SecondaryButton';

function buildStructurePreview(ancestors: { id: string; name: string }[]) {
  if (ancestors.length > 0) {
    return (
      <ol>
        <li
          key={ancestors[0].id}
          className={combineClassNames(
            'py-2',
            ancestors.length === 1
              ? 'font-bold bg-gray-100 px-4 rounded-lg'
              : ''
          )}>
          {ancestors[0].name}
        </li>
        <li className="pl-5 border-l">
          {buildStructurePreview(ancestors.slice(1))}
        </li>
      </ol>
    );
  } else {
    return <></>;
  }
}

interface NewStudentGroupPreviewProps {
  data: GroupCreationFormData;
  onPreviousRequested: () => void;
  onAdvance: () => void;
  disabled?: boolean;
}

export default function NewStudentGroupPreview({
  data,
  onPreviousRequested,
  onAdvance,
  disabled,
}: NewStudentGroupPreviewProps) {
  return (
    <div>
      <div className="px-4 sm:px-0">
        <h3 className="text-base font-semibold leading-7 text-gray-900">
          Preview
        </h3>
        <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
          Your last chance to preview the new group's information before it is
          created.
        </p>
      </div>
      <div className="mt-6 border-t border-gray-100">
        <dl className="divide-y divide-gray-100">
          {/* Name */}
          <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt className="text-sm font-medium leading-6 text-gray-900">
              Name
            </dt>
            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
              {data.name.length > 0 ? (
                data.name
              ) : (
                <span className="text-gray-500">N/A</span>
              )}
            </dd>
          </div>
          {/* Parent type */}
          <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt className="text-sm font-medium leading-6 text-gray-900">
              Parent type
            </dt>
            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
              {data.parent ? (
                <div className="flex items-center gap-2">
                  {/* Picture */}
                  <div>
                    {data.parentType === 'institution' &&
                      ((data.parent as InstitutionViewModel).pictureUri ? (
                        <img
                          src={
                            (data.parent as InstitutionViewModel).pictureUri ??
                            ''
                          }
                          alt={data.parent.name}
                          className="size-12 rounded-full bg-gray-50"
                        />
                      ) : (
                        <div
                          className="size-12 bg-gray-800 flex items-center justify-center rounded-full text-white"
                          aria-hidden="true">
                          <BuildingLibraryIcon className="size-8" />
                        </div>
                      ))}
                    {data.parentType === 'studentGroup' && (
                      <div
                        className="size-12 bg-gray-800 flex items-center justify-center rounded-full text-white"
                        aria-hidden="true">
                        <UserGroupIcon className="size-8" />
                      </div>
                    )}
                  </div>

                  <div>
                    <h5 className="font-semibold">{data.parent.name}</h5>
                    <p className="text-sm text-gray-500">
                      {data.parentType === 'institution'
                        ? 'Institution'
                        : 'Student group'}
                    </p>
                  </div>
                </div>
              ) : (
                <span className="text-gray-500">N/A</span>
              )}
            </dd>
          </div>

          {data.parent && (
            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
              <dt className="text-sm font-medium leading-6 text-gray-900">
                Structure preview
              </dt>
              <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                {buildStructurePreview([
                  ...data.parent.ancestors,
                  data.parent,
                  {
                    id: 'new',
                    name:
                      data.name.trim().length > 0
                        ? data.name
                        : 'The group you are creating',
                  },
                ])}
              </dd>
            </div>
          )}
        </dl>
      </div>
      <div className="mt-6 flex items-center justify-end gap-x-3 pt-6 border-t">
        <SecondaryButton
          disabled={disabled}
          type="button"
          onClick={() => {
            onPreviousRequested();
          }}>
          Back
        </SecondaryButton>
        <PrimaryButton
          disabled={disabled}
          type="submit"
          onClick={() => {
            onAdvance();
          }}>
          Save group
        </PrimaryButton>
      </div>
    </div>
  );
}
