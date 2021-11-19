import { ActionContext, ActionTree, DispatchOptions } from "vuex";
import { nanoid } from "nanoid";

import { RootAugmentedActionContext, RootState } from "../..";

import { State } from "./state";
import { Mutations } from "./mutations";

import { AppActionTypes as ActionTypes } from "./action-types";
import { AppMutationTypes as MutationTypes } from "./mutation-types";
import { Getters } from "./getters";

export type AugmentedActionContext = {
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

export interface Actions { }


export const actions: ActionTree<State, RootState> & Actions = {};
