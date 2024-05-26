import { Bounce, toast, ToastContainer, TypeOptions } from 'react-toastify';
import { PropsWithChildren, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

function displayPageToasts(toasts: { [type in TypeOptions]: string[] }) {
  Object.entries(toasts).forEach(([type, messages]) => {
    messages.forEach(message => {
      toast(message, {
        type: type as keyof typeof toasts,
        position: 'bottom-right',
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: 'light',
        transition: Bounce,
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
