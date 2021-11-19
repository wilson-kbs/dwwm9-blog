import { GetterTree } from "vuex";

import { RootState } from "../..";
import { State } from "./state";

export type Getters = {
  AUTH_FORM__email(state: State): string;
  AUTH_FORM__username(state: State): string;
  AUTH_FORM__password(state: State): string;
  AUTH_FORM__confirmPassword(state: State): string;
  AUTH__IsAuth(state: State): boolean;
};

export const getters: GetterTree<State, RootState> = {
  AUTH_FORM__email: (state: State) => state.authForm.email,
  AUTH_FORM__username: (state: State) => state.authForm.username,
  AUTH_FORM__password: (state: State) => state.authForm.password,
  AUTH_FORM__confirmPassword: (state: State) => state.authForm.confirmPassword,
  AUTH__IsAuth: (state: State) =>
    state.sessionExp && state.sessionExp.getTime() > Date.now(),
};
