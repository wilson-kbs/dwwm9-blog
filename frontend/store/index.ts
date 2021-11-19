import { createStore } from "vuex";

import {
  AppStore,
  store as app,
  State as AppState,
  AppAugmentedActionContext
} from "./modules/app";

import {
  CategoriesStore,
  store as categories,
  State as CategoriesState,
  CategoriesAction,
  CategoriesAugmentedActionContext,
} from "./modules/categories";

import {
  PostsStore,
  store as posts,
  State as PostsState,
} from "./modules/posts";

import {
  CommentsStore,
  store as comments,
  State as CommentsState,
} from "./modules/comments";

import {
  AuthStore,
  store as auth,
  State as AuthState,
  AuthAction,
  AuthAugmentedActionContext
} from "./modules/auth";

export type RootAugmentedActionContext = CategoriesAugmentedActionContext & AuthAugmentedActionContext & AppAugmentedActionContext

export type State = {
  appName: string
}

export type RootState = State & {
  app: AppState;
  categories: CategoriesState;
  posts: PostsState;
  comments: CommentsState;
  auth: AuthState;
};

export type Store = { state: State } & AppStore<Pick<RootState, "app">> & CategoriesStore<Pick<RootState, "categories">> &
  PostsStore<Pick<RootState, "posts">> &
  CommentsStore<Pick<RootState, "comments">> &
  AuthStore<Pick<RootState, "auth">>;

export const store = createStore<State>({
  state: {
    appName: 'dwwm9pjr'
  },
  modules: {
    app,
    categories,
    posts,
    comments,
    auth,
  },
});

export function useStore(): Store {
  return store as unknown as Store;
}
