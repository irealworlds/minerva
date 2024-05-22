import { Config } from 'ziggy-js';

export interface User {
    id: number;
    email: string;
    emailVerified: boolean;
    pictureUri: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};
