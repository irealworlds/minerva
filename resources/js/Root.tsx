import {
    Bounce,
    toast,
    ToastContainer,
    ToastOptions,
    TypeOptions,
} from 'react-toastify';
import { PropsWithChildren, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export const AppToastOptions: ToastOptions = {
    position: 'bottom-right',
    autoClose: 5000,
    hideProgressBar: false,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
    progress: undefined,
    theme: 'light',
    transition: Bounce,
};

function displayPageToasts(toasts: { [type in TypeOptions]: string[] }) {
    Object.entries(toasts).forEach(([type, messages]) => {
        messages.forEach(message => {
            toast(message, {
                ...AppToastOptions,
                type: type as keyof typeof toasts,
            });
        });
    });
}

export default function Root({ children }: PropsWithChildren) {
    const { toasts } = usePage<PageProps>().props;
    useEffect(() => {
        displayPageToasts(toasts);
    }, [toasts]);

    return (
        <>
            {children}
            <ToastContainer />
        </>
    );
}
