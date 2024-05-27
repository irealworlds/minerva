import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';
import { Permission } from '@/types/permission.enum';

/**
 * Check if the user has access to view content that is protected by permissions.
 *
 * Sets are chained by AND operations, while the items inside each set are chained by OR operations.
 *
 * @param user
 * @param sets
 */
export function checkPermissionsForUser(
  user: AuthenticatedUserViewModel,
  ...sets: Permission[] | Permission[][]
) {
  for (let permissionSet of sets) {
    if (!Array.isArray(permissionSet)) {
      permissionSet = [permissionSet];
    }

    // If no item in the permissionSet is included in the user's permissions,
    // then fail the check
    if (!user.permissions.some(p => permissionSet.includes(p))) {
      return false;
    }
  }
  return true;
}
