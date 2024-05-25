import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';
import { Permission } from '@/types/permission.enum';

export function hasPermission(
  user: AuthenticatedUserViewModel,
  permissions: Permission | Permission[] | undefined
) {
  if (permissions === undefined) {
    return false;
  }

  if (!Array.isArray(permissions)) {
    permissions = [permissions];
  }

  return user.permissions.some(p => permissions.includes(p));
}
