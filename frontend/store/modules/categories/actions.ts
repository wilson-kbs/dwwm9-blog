import { ActionContext, ActionTree, DispatchOptions } from "vuex";
import { nanoid } from "nanoid";

import { RootAugmentedActionContext, RootState } from "../..";

import { State } from "./state";
import { Mutations } from "./mutations";

import { CategoriesActionTypes as ActionTypes } from "./action-types";
import { CategoriesMutationTypes as MutationTypes } from "./mutation-types";
import { Getters } from "./getters";
import { Category } from "../../../interfaces/category";
import { API_ENDPOINTS, SlugFromChar } from "../../../utils";

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

export interface Actions {
  [ActionTypes.GET_CATEGORIES](
    { state, commit }: RootAugmentedActionContext
  ): Promise<Category[]>
  [ActionTypes.GET_CATEGORY](
    { state, commit }: RootAugmentedActionContext,
    id: string | number
  ): Promise<Category>
}

export const actions: ActionTree<State, RootState> & Actions = {
  async [ActionTypes.GET_CATEGORIES]({ commit }) {
    async function checkResponse(res: Response) {
      if (res.statusText === "OK") {
        const responseJson: Category[] = await res.json();
        return responseJson
      } else throw res;
    }

    try {
      const categories = await fetch(API_ENDPOINTS.CATEGORIES, {
        method: "GET",
        headers: {
          "content-type": "application/json",
          accept: "application/json",
        },
      }).then(checkResponse);
      console.log(categories)
      for (const categ of categories) {
        categ.slug = SlugFromChar(categ.name)
      }
      commit(MutationTypes.SET_CATEGORIES, categories);
      return categories
    } catch (error) {
      console.error(error);
    }
  },
  async [ActionTypes.GET_CATEGORY]({ commit, getters }, id) {
    async function checkResponse(res: Response) {
      console.log(res)
      if (res.statusText === "OK") {
        const responseJson: Category = await res.json();
        return responseJson
      } else throw res;
    }

    const headers = new Headers()
    headers.append("content-type", "application/json")
    headers.append("accept", "application/json")

    if (getters.AUTH__IsAuth) {
      const token = localStorage.getItem('token')
      headers.append("Authorization", `Bearer ${token}`)
    }


    try {
      const category = await fetch(`${API_ENDPOINTS.CATEGORIES}/${id}`, {
        method: "GET",
        headers,
      }).then(checkResponse);
      console.log(category)
      category.slug = SlugFromChar(category.name)
      return category
    } catch (error) {
      console.error(error);
      throw error;
    }
  }
};
