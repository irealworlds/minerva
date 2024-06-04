import { Permission } from '@/types/permission.enum';

export interface AuthenticatedUserViewModel {
    id: number;
    name: {
        prefix: string;
        firstName: string;
        middleNames: string[];
        lastName: string;
        suffix: string;
    };
    email: string;
    emailVerified: boolean;
    pictureUri: string;
    permissions: Permission[];
}
