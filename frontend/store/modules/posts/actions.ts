import { ActionContext, ActionTree, DispatchOptions } from "vuex";
import { nanoid } from "nanoid";

import { RootState } from "../..";

import { State } from "./state";
import { Mutations } from "./mutations";

import { PostsActionTypes as ActionTypes } from "./action-types";
import { PostsMutationTypes as MutationTypes } from "./mutation-types";
import { Getters } from "./getters";
import { API_ENDPOINTS } from "../../../utils";
import { FullPost, ShortPost } from "../../../interfaces";
import { IsValid as IsValidToken } from "../../../utils/jwt";

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

export interface Actions {
  [ActionTypes.FIND_ONE](
    { commit, dispatch }: AugmentedActionContext,
    id: number
  ): Promise<FullPost | undefined>;
  [ActionTypes.GET_LAST_PUBLISHED]({ commit, dispatch }: AugmentedActionContext, numItem?: number): Promise<ShortPost[] | undefined>
}

export const actions: ActionTree<State, RootState> & Actions = {
  async [ActionTypes.FIND_ONE]({ commit, dispatch }, payload) {
    async function checkeResponse(res: Response) {
      if (res.statusText === "OK") {
        const responseJson: FullPost = await res.json();
        return responseJson;
      }
    }

    try {
      const headers = new Headers();
      const token = localStorage.getItem("token");

      if (token && IsValidToken(token)) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      const url = API_ENDPOINTS.POSTS + `/${payload}`;
      const post = fetch(url, {
        headers,
      }).then(checkeResponse);

      return post;
    } catch (error) {
      return undefined;
    }
  },

  async [ActionTypes.GET_LAST_PUBLISHED](_, numItem = 5) {
    async function checkeResponse(res: Response) {
      if (res.statusText === "OK") {
        console.log(res)
        const responseJson: ShortPost[] = await res.json();
        return responseJson;
      }
    }

    try {
      const headers = new Headers();
      const token = localStorage.getItem("token");

      if (token && IsValidToken(token)) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      const url = API_ENDPOINTS.POSTS + `?itemsPerPage=${numItem}`;
      const post = fetch(url, {
        headers,
      }).then(checkeResponse);

      return post;
    } catch (error) {
      return undefined;
    }
  }
};
