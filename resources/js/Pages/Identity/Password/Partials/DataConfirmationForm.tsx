import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { CheckIcon } from '@heroicons/react/20/solid';

interface DataConfirmationFormProps {
    data: {
        idNumber: string;
        name: string;
        emailAddress: string;
    };
    onAdvance: () => void;
}

export default function DataConfirmationForm({
    data,
    onAdvance,
}: DataConfirmationFormProps) {
    return (
        <div className="space-y-6">
            {/* Id number */}
            <div>
                <InputLabel htmlFor="idNumber" value="Id number" />

                <TextInput
                    type="text"
                    readOnly={true}
                    value={data.idNumber}
                    className="mt-1 block w-full"
                />

                <p
                    className="mt-2 text-sm text-gray-500"
                    id="email-description">
                    As seen on your identity document.
                </p>
            </div>

            {/* Name */}
            <div>
                <InputLabel htmlFor="name" value="Name" />

                <TextInput
                    type="text"
                    readOnly={true}
                    value={data.name}
                    className="mt-1 block w-full"
                />
            </div>

            {/* Email address */}
            <div>
                <InputLabel htmlFor="emailAddress" value="Email address" />

                <TextInput
                    type="text"
                    readOnly={true}
                    value={data.emailAddress}
                    className="mt-1 block w-full"
                />
            </div>

            {/* Actions */}
            <div className="flex items-center justify-stretch md:justify-end">
                <PrimaryButton
                    type="button"
                    onClick={() => {
                        onAdvance();
                    }}>
                    <CheckIcon className="size-4 mr-2" />
                    Confirm information
                </PrimaryButton>
            </div>
        </div>
    );
}
