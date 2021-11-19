type VerifToken = {
  exp: number;
  iat: number;
};
export function Decode<T>(token: string): T | undefined {
  try {
    return JSON.parse(atob(token.split(".")[1]));
  } catch (e) {
    return undefined;
  }
}

export function IsValid(token: string) {
  const decode = Decode<VerifToken>(token);
  if (!decode) return false;

  const jwtExp = decode.exp * 1_000;
  if (jwtExp > Date.now()) return true;

  return false;
}
