import { ActionContext, ActionTree, DispatchOptions } from "vuex";
import { nanoid } from "nanoid";

import { RootState } from "../..";

import { State } from "./state";
import { Mutations } from "./mutations";

import { CommentsActionTypes as ActionTypes } from "./action-types";
import { CommentsMutationTypes as MutationTypes } from "./mutation-types";
import { Getters } from "./getters";

type AugmentedActionContext = {
  dispatch<K extends keyof Actions>(
    key: K,
    payload?: Parameters<Actions[K]>[1],
    options?: DispatchOptions
  ): ReturnType<Actions[K]>;
} & {
  commit<K extends keyof Mutations>(
    key: K,
    payload: Parameters<Mutations[K]>[1]
  ): ReturnType<Mutations[K]>;
} & {
  getters: {
    [K in keyof Getters]: ReturnType<Getters[K]>;
  };
} & Omit<ActionContext<State, RootState>, "commit" | "getters" | "dispatch">;

export interface Actions {}

export const actions: ActionTree<State, RootState> & Actions = {};
