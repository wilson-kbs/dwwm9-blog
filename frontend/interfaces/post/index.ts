import { Redactor } from "..";
import { Category } from "../category";

export type Owner = {
  id: number;
  username: string;
};

export type ShortPost = {
  id: number;
  title: string;
  description: string;
  imgUrl: string;
  redactor: Redactor;
  updatedAt?: string;
  publishedAt: string;
};

export type FullPost = ShortPost & {
  content: string;
  category: Category;
};
