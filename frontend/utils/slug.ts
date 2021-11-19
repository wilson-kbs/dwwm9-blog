import { RemoveDiacritics } from "./diacritics";

export function SlugFromChar(str: string) {
  return RemoveDiacritics(str).replace(/[^A-Za-z0-9_.\-~ ]/gi, '').trim().replace(/[ ]+/gi, '-')
}