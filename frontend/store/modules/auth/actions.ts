import { ActionContext, ActionTree, DispatchOptions } from "vuex";
import { nanoid } from "nanoid";

import { RootState } from "../..";

import { State } from "./state";
import { Mutations } from "./mutations";

import { AuthActionTypes as ActionTypes } from "./action-types";
import { AuthMutationTypes as MutationTypes } from "./mutation-types";
import { Getters } from "./getters";
import { API_ENDPOINTS } from "../../../utils";
import {
  AuthResponse,
  Credential,
  Register,
  TokenDecode,
} from "../../../interfaces";

import { Decode as jwtDecode } from "../../../utils/jwt";

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
  [ActionTypes.SIGN_UP]({ commit, dispatch }: AugmentedActionContext): void;

  [ActionTypes.LOGIN]({ commit, dispatch }: AugmentedActionContext): void;

  [ActionTypes.CHECK_USER_IS_AUTH]({ commit }: AugmentedActionContext): boolean;
}

export const actions: ActionTree<State, RootState> & Actions = {
  async [ActionTypes.SIGN_UP]({ state, dispatch }) {
    console.log("Inscription");
    if (
      !state.authForm.email ||
      !state.authForm.password ||
      !state.authForm.username
    ) {
      console.error("email or username or password is empy");
      return;
    }
    const registerUser: Register = {
      email: state.authForm.email,
      username: state.authForm.username,
      password: state.authForm.password,
    };

    async function checkResponse(res: Response) {
      if (res.statusText === "OK") {
        const responseJson: AuthResponse = await res.json();
        return responseJson.token;
      } else throw res;
    }

    try {
      const token = await fetch(API_ENDPOINTS.SIGNUP, {
        method: "POST",
        headers: {
          "content-type": "application/json",
          accept: "application/json",
        },
        body: JSON.stringify(registerUser),
      }).then(checkResponse);
      localStorage.setItem("token", token);
      dispatch(ActionTypes.CHECK_USER_IS_AUTH);
    } catch (error) {
      console.error(error);
    }
  },

  async [ActionTypes.LOGIN]({ state, dispatch }) {
    console.log("Connection");
    if (!state.authForm.email || !state.authForm.password) {
      console.error("email or password is empy");
      return;
    }
    const credential: Credential = {
      username: state.authForm.email,
      password: state.authForm.password,
    };

    async function checkResponse(res: Response) {
      if (res.statusText === "OK") {
        const responseJson: AuthResponse = await res.json();
        return responseJson.token;
      } else throw res;
    }

    try {
      const token = await fetch(API_ENDPOINTS.LOGIN, {
        method: "POST",
        headers: {
          "content-type": "application/json",
          accept: "application/json",
        },
        body: JSON.stringify(credential),
      }).then(checkResponse);
      localStorage.setItem("token", token);
      dispatch(ActionTypes.CHECK_USER_IS_AUTH);
    } catch (error) {
      console.error(error);
    }
  },

  [ActionTypes.CHECK_USER_IS_AUTH]({ commit }): boolean {
    const token = localStorage.getItem("token");

    if (!token) return false;

    const tokenDecode = jwtDecode<TokenDecode>(token);

    if (!tokenDecode) return false;

    const dateNow = Date.now() / 1_000;
    const jwtExp = tokenDecode.exp;

    if (dateNow > jwtExp) return false;

    commit(MutationTypes.SESSION_EXP, new Date(jwtExp * 1_000));

    return true;
  },
};
