import {
  Store as VuexStore,
  DispatchOptions,
  CommitOptions,
  Module,
} from "vuex";

import { RootState } from "../..";

import { state } from "./state";
import { getters, Getters } from "./getters";
import { mutations, Mutations } from "./mutations";
import { actions, Actions, AugmentedActionContext } from "./actions";

import type { State } from "./state";

export { State, Actions as AuthAction, AugmentedActionContext as AuthAugmentedActionContext };

export const Namespace = "auth";

export type AuthStore<S = State> = Omit<
  VuexStore<S>,
  "getters" | "commit" | "dispatch"
> & {
  commit<K extends keyof Mutations, P extends Parameters<Mutations[K]>[1]>(
    key: K,
    payload: P,
    options?: CommitOptions
  ): ReturnType<Mutations[K]>;
} & {
  dispatch<K extends keyof Actions>(
    key: K,
    payload?: Parameters<Actions[K]>[1],
    options?: DispatchOptions
  ): ReturnType<Actions[K]>;
} & {
  getters: {
    [K in keyof Getters]: ReturnType<Getters[K]>;
  };
};

export const store: Module<State, RootState> = {
  state,
  getters,
  mutations,
  actions,
};
