import { MouseEvent, useState } from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { InstitutionViewModel } from "@/types/ViewModels/institution.view-model";
import DangerButton from "@/Components/DangerButton";
import { router } from "@inertiajs/react";
import axios from "axios";


export default function DeleteInstitutionForm({ institution }: { institution: InstitutionViewModel }) {
    const [nameConfirmation, setNameConfirmation] = useState("");
    const [processing, setProcessing] = useState(false);

    async function deleteInstitution(e: MouseEvent<any, any>): Promise<any> {
        if(processing) {
            return;
        }

        e.stopPropagation();

        setProcessing(true);

        axios.delete(route("api.institutions.delete", {
            institution: institution.id,
        })).then(() => {
            router.visit(route("institutions.index"));
        }).finally(() => {
            setProcessing(false);
        });
    }

    return (
        <>

            <div className="bg-white p-6 rounded-lg shadow">
                <div className="border-b border-gray-900/10 pb-12">
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Remove institution
                    </h2>
                    <p className="mt-1 text-sm leading-6 text-gray-600">
                        Remove this institution and all associated information from the system.
                    </p>

                    <div className="mt-10 grid grid-cols-1 sm:grid-cols-6">
                        <div className="sm:col-span-4">
                            <p className="text-sm mb-4">
                                In order to confirm your intention to delete this institution and all associated
                                information,
                                please type <span className="font-semibold">"{institution.name}"</span> into the box
                                below.
                            </p>

                            <InputLabel htmlFor="confirmation" value="Name confirmation"/>

                            <TextInput
                                id="confirmation"
                                type="text"
                                name="confirmation"
                                className="mt-1 block w-full"
                                placeholder={institution.name}
                                isFocused={true}
                                onChange={(e) => setNameConfirmation(e.target.value)}
                            />
                        </div>
                    </div>
                </div>

                <div className="mt-6 flex items-center justify-end gap-x-6">
                    <DangerButton type="button" disabled={nameConfirmation !== institution.name || processing} onClick={e => deleteInstitution(e)}>
                        {processing
                            ? "Deleting"
                            : "Delete"}
                    </DangerButton>
                </div>
            </div>
        </>
    );
}
