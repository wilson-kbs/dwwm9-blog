import { MutationTree } from "vuex";

import type { State } from "./state";

export type Mutations<S = State> = {};

export const mutations: MutationTree<State> & Mutations = {};
