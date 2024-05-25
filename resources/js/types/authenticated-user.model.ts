import { Permission } from '@/types/permission.enum';

export interface AuthenticatedUserViewModel {
  id: number;
  email: string;
  emailVerified: boolean;
  pictureUri: string;
  permissions: Permission[];
}
