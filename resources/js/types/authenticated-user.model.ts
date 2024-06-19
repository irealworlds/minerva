import { Permission } from '@/types/permission.enum';
import { PersonalNameDto } from '@/types/dtos/personal-name.dto';

export interface AuthenticatedUserViewModel {
    id: number;
    name: PersonalNameDto;
    email: string;
    emailVerified: boolean;
    pictureUri: string;
    permissions: Permission[];
}
