import { Redactor } from "../redactor";

export type PostHasJSON = {
  id: number;
  title: string;
  slug: string;
  redactor: Redactor;
}