import { MutationTree } from "vuex";

import { AppMutationTypes as MutationTypes } from "./mutation-types"

import type { State } from "./state";

export type Mutations<S = State> = {
  [MutationTypes.IS_MODAL_ACTIVE](state: S, payload: boolean): void
};

export const mutations: MutationTree<State> & Mutations = {
  [MutationTypes.IS_MODAL_ACTIVE](state, payload) {
    state.isModalActive = payload
  },
};
