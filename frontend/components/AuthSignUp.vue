<template>
  <form action="/api/signup" method="post" @submit="onFormSubmit">
    <KSInputText
      class="auth-form-input"
      type="text"
      title="Username"
      autocomplete="username"
      placeHolder="Username"
      v-model="username"
    />

    <KSInputText
      class="auth-form-input"
      type="email"
      title="Email"
      autocomplete="email"
      placeHolder="Email"
      v-model="email"
    />

    <KSInputText
      class="auth-form-input"
      type="password"
      title="Mot de Passe"
      autocomplete="new-password"
      placeHolder="Mot de passe"
      v-model="password"
    />

    <KSInputText
      class="auth-form-input"
      type="password"
      title="Confirmer"
      autocomplete="new-password"
      placeHolder="Confirmer"
      v-model="confirmPassword"
    />

    <div class="actions">
      <button>Sign Up</button>
    </div>
  </form>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { AuthActionTypes } from "../store/modules/auth/action-types";
import { AuthMutationTypes } from "../store/modules/auth/mutation-types";
import KSInputText from "./common/KsInput";

export default defineComponent({
  components: {
    KSInputText,
  },
  setup() {
    return {};
  },
  computed: {
    email: {
      get() {
        return this.$store.getters.AUTH_FORM__email;
      },
      set(value: string) {
        this.$store.commit(AuthMutationTypes.FORM_EMAIL, value);
      },
    },
    username: {
      get() {
        return this.$store.getters.AUTH_FORM__username;
      },
      set(value: string) {
        this.$store.commit(AuthMutationTypes.FORM_USERNAME, value);
      },
    },
    password: {
      get() {
        return this.$store.getters.AUTH_FORM__password;
      },
      set(value: string) {
        this.$store.commit(AuthMutationTypes.FORM_PASSWORD, value);
      },
    },
    confirmPassword: {
      get() {
        return this.$store.getters.AUTH_FORM__confirmPassword;
      },
      set(value: string) {
        this.$store.commit(AuthMutationTypes.FORM_CONFIRM_PASSWORD, value);
      },
    },
  },
  methods: {
    onFormSubmit(e: any) {
      e.preventDefault();
      if (this.password !== this.confirmPassword) {
        alert("Confimation de mot de passe non valide !");
        return;
      }
      this.$store.dispatch(AuthActionTypes.SIGN_UP);
    },
  },
});
</script>

<style scoped>
</style>