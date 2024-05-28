import { BuildingLibraryIcon } from '@heroicons/react/24/outline';
import React from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface InstitutionPictureProps {
    uri: string | null;
    className: string;
}

export default function InstitutionPicture({
    uri,
    className,
}: InstitutionPictureProps) {
    return uri ? (
        <img
            className={combineClassNames(
                'flex-none rounded-full bg-gray-50 border shadow',
                className
            )}
            src={uri}
            alt=""
        />
    ) : (
        <div
            className={combineClassNames(
                'bg-gray-800 flex items-center justify-center rounded-full text-white',
                className
            )}
            aria-hidden="true">
            <BuildingLibraryIcon className="size-[75%]" />
        </div>
    );
}
