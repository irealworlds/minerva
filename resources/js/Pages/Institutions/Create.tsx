import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import React, { FormEventHandler, useEffect, useRef, useState } from 'react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import SecondaryButton from '@/Components/SecondaryButton';
import PrimaryButton from '@/Components/PrimaryButton';
import { BuildingLibraryIcon } from '@heroicons/react/24/outline';
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
  Label,
} from '@headlessui/react';
import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';
import { useDebouncedCallback } from 'use-debounce';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { PaginatedCollection } from '@/types/paginated-result.contract';

interface CreationForm {
  name: string;
  website: string;
  picture: Blob | null;
  parent: InstitutionViewModel | null;
}

type CreationRequestData = Omit<CreationForm, 'parent'> & {
  parentInstitutionId: string | null;
};

export default function Create({ auth }: PageProps) {
  const { data, setData, post, processing, errors, transform } =
    useForm<CreationForm>({
      name: '',
      website: '',
      picture: null,
      parent: null,
    });
  transform(
    formData =>
      ({
        name: formData.name,
        website: formData.website,
        picture: formData.picture,
        parentInstitutionId: formData.parent?.id ?? null,
      }) as CreationRequestData as unknown as CreationForm
  );

  const [parentQuery, setParentQuery] = useState('');
  const [filteredParents, setFilteredParents] = useState<
    InstitutionViewModel[]
  >([]);

  const updateParents = useDebouncedCallback(async (searchQuery: string) => {
    // todo Replace with axios
    const response = await fetch(
      route('api.institutions.index', {
        search: searchQuery,
      }),
      {
        headers: {
          accept: 'application/json',
        },
      }
    );
    if (response.ok) {
      const body = (await response.json()) as unknown as {
        institutions: PaginatedCollection<InstitutionViewModel>;
      };
      setFilteredParents(body.institutions.data);
    }
  }, 400);
  useEffect(() => {
    updateParents(parentQuery)?.then(
      () => {
        // Do nothing
      },
      () => {
        // Do nothing
      }
    );
  }, [parentQuery]);

  const pictureInput = useRef<HTMLInputElement>(null);
  const [pictureInputPreview, setPictureInputPreview] = useState<string | null>(
    null
  );

  const updatePictureInputValue = (newValue: File | null) => {
    // Update the preview data URL
    setPictureInputPreview(null);
    if (newValue) {
      const reader = new FileReader();
      reader.onload = function () {
        if (typeof reader.result === 'string') {
          setPictureInputPreview(reader.result);
        }
      };
      reader.readAsDataURL(newValue);
    }

    // Update the form input
    setData('picture', newValue);
  };

  const submit: FormEventHandler = e => {
    e.preventDefault();

    post(route('institutions.store'), {
      forceFormData: true,
    });
  };

  return (
    <AuthenticatedLayout user={auth.user}>
      <Head title="Create institution" />

      <form onSubmit={submit} encType="multipart/form-data">
        <div className="space-y-10 container mx-auto mb-6">
          <div className="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
            <div className="px-4 sm:px-0">
              <h2 className="text-base font-semibold leading-7 text-gray-900">
                Public profile
              </h2>
              <p className="mt-1 text-sm leading-6 text-gray-600">
                Information about this institution that will be publicly
                accessible to all users.
              </p>
            </div>

            <div className="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
              <div className="px-4 py-6 sm:p-8">
                <div className="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  {/* Name */}
                  <div className="sm:col-span-4">
                    <InputLabel htmlFor="name" value="Name" />

                    <TextInput
                      id="name"
                      type="text"
                      name="name"
                      value={data.name}
                      className="mt-1 block w-full"
                      placeholder="This institution's name"
                      onChange={e => {
                        setData('name', e.target.value);
                      }}
                    />

                    <InputError message={errors.name} className="mt-2" />
                  </div>

                  {/* Website */}
                  <div className="sm:col-span-4">
                    <InputLabel htmlFor="website" value="Website" />

                    <TextInput
                      id="website"
                      type="text"
                      name="website"
                      value={data.website}
                      className="mt-1 block w-full"
                      placeholder="A link to the institution's website"
                      onChange={e => {
                        setData('website', e.target.value);
                      }}
                    />

                    <InputError message={errors.website} className="mt-2" />
                  </div>

                  {/* Parent */}
                  <div className="sm:col-span-4">
                    <Combobox
                      as="div"
                      value={data.parent}
                      onChange={value => {
                        setData('parent', value);
                      }}
                      onClose={() => {
                        setParentQuery('');
                      }}
                    >
                      <Label className="block text-sm font-medium leading-6 text-gray-900">
                        Parent institution
                      </Label>
                      <div className="relative mt-2">
                        <ComboboxInput
                          className="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                          onChange={event => {
                            setParentQuery(event.target.value);
                          }}
                          onBlur={() => {
                            setParentQuery('');
                          }}
                          placeholder="The institution to which this entity is subordinated"
                          displayValue={(
                            institution: InstitutionViewModel | null
                          ) => institution?.name ?? ''}
                        />
                        <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                          <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                          />
                        </ComboboxButton>

                        {filteredParents.length > 0 && (
                          <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {filteredParents.map(parent => (
                              <ComboboxOption
                                key={parent.id}
                                value={parent}
                                className={({ focus }) =>
                                  combineClassNames(
                                    'relative cursor-pointer select-none py-2 pl-3 pr-9',
                                    focus
                                      ? 'bg-indigo-600 text-white'
                                      : 'text-gray-900'
                                  )
                                }
                              >
                                {({ focus, selected }) => (
                                  <>
                                    <span
                                      className={combineClassNames(
                                        'block truncate',
                                        selected ? 'font-semibold' : ''
                                      )}
                                    >
                                      {parent.name}
                                    </span>

                                    {selected && (
                                      <span
                                        className={combineClassNames(
                                          'absolute inset-y-0 right-0 flex items-center pr-4',
                                          focus
                                            ? 'text-white'
                                            : 'text-indigo-600'
                                        )}
                                      >
                                        <CheckIcon
                                          className="size-5"
                                          aria-hidden="true"
                                        />
                                      </span>
                                    )}
                                  </>
                                )}
                              </ComboboxOption>
                            ))}
                          </ComboboxOptions>
                        )}
                      </div>
                    </Combobox>
                    <InputError message={errors.parent} className="mt-2" />
                  </div>

                  {/* Picture */}
                  <div className="col-span-full">
                    <label
                      htmlFor="photo"
                      className="block text-sm font-medium leading-6 text-gray-900"
                    >
                      Picture
                    </label>
                    <input
                      id="picture"
                      type="file"
                      accept="image/*"
                      className="hidden"
                      ref={pictureInput}
                      onChange={e => {
                        updatePictureInputValue(
                          e.target.files?.item(0) ?? null
                        );
                      }}
                    />

                    <div className="mt-2 flex items-center gap-x-3">
                      {pictureInputPreview ? (
                        <img
                          src={pictureInputPreview}
                          className="size-12 rounded-full border"
                          alt="Upload preview"
                        />
                      ) : (
                        <div
                          className="size-12 bg-gray-800 flex items-center justify-center rounded-full text-white"
                          aria-hidden="true"
                        >
                          <BuildingLibraryIcon className="size-8" />
                        </div>
                      )}
                      {!!pictureInputPreview?.length && (
                        <SecondaryButton
                          type="button"
                          onClick={() => {
                            updatePictureInputValue(null);
                          }}
                        >
                          Clear
                        </SecondaryButton>
                      )}
                      <PrimaryButton
                        type="button"
                        onClick={() => pictureInput.current?.click()}
                      >
                        Change
                      </PrimaryButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3 sticky bottom-5">
            <div className="hidden md:block" />
            <div className="md:col-span-2 bg-white rounded-lg flex items-center justify-end gap-x-6 shadow border-gray-900/10 px-4 py-4 sm:px-8">
              <Link href={route('institutions.index')}>
                <SecondaryButton disabled={processing}>
                  Back to list
                </SecondaryButton>
              </Link>
              <PrimaryButton type="submit" disabled={processing}>
                {processing ? 'Saving' : 'Save institution'}
              </PrimaryButton>
            </div>
          </div>
        </div>
      </form>
    </AuthenticatedLayout>
  );
}
