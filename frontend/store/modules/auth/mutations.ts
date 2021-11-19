import { MutationTree } from "vuex";

import type { State } from "./state";

import { AuthMutationTypes as MutationTypes } from "./mutation-types";

export type Mutations<S = State> = {
  [MutationTypes.FORM_EMAIL](state: S, payload: string): void;
  [MutationTypes.FORM_USERNAME](state: S, payload: string): void;
  [MutationTypes.FORM_PASSWORD](state: S, payload: string): void;
  [MutationTypes.FORM_CONFIRM_PASSWORD](state: S, payload: string): void;
  [MutationTypes.SESSION_EXP](state: S, payload: Date): void;
};

export const mutations: MutationTree<State> & Mutations = {
  [MutationTypes.FORM_EMAIL](state, payload) {
    state.authForm.email = payload;
  },
  [MutationTypes.FORM_USERNAME](state, payload) {
    state.authForm.username = payload;
  },
  [MutationTypes.FORM_PASSWORD](state, payload) {
    state.authForm.password = payload;
  },
  [MutationTypes.FORM_CONFIRM_PASSWORD](state, payload) {
    state.authForm.confirmPassword = payload;
  },
  [MutationTypes.SESSION_EXP](state, payload) {
    state.sessionExp = payload;
  },
};
