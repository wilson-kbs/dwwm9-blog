type CredentialsOrRegister = {
  email: string;
  username: string;
  password: string;
  confirmPassword: string;
};

export type State = {
  isAuth: boolean;
  sessionExp?: Date;
  authForm: CredentialsOrRegister;
};
export const state: State = {
  isAuth: false,
  authForm: {
    email: "john@doe.fr",
    username: "",
    password: "0000",
    confirmPassword: "",
  },
};
