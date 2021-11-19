import { MutationTree } from "vuex";

import { state, State } from "./state";
import { CategoriesMutationTypes as MutationTypes } from './mutation-types'
import { Category } from "../../../interfaces";

export type Mutations<S = State> = {
  [MutationTypes.SET_CATEGORIES](state: S, categories: Category[]): void
};

export const mutations: MutationTree<State> & Mutations = {
  [MutationTypes.SET_CATEGORIES](state, category) {
    state.list = category
    console.log(state.list)
    // state.list.splice(0, state.list.length);
    // state.list.push(...category)
  },

};
