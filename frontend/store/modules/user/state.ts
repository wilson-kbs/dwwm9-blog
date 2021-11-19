type UserRoles = 'ROLES_USER' | 'ROLES_REDACTOR' | 'ROLES_ADMIN';

export type State = {
  id?: number
  roles?: UserRoles[]
};
export const state: State = {

};
