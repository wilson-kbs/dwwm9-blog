export type TokenDecode = {
  id: number;
  email: string;
  roles: string[];
  iat: number;
  exp: number;
};