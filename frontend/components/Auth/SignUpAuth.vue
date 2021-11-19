<template>
  <div>
    <form action="/api/signup" method="post" @submit="onFormSubmit">
      <div class="auth-form-input">
        <label for="username">Username</label>
        <input
          v-model="username"
          type="text"
          id="username"
          name="username"
          autocomplete="username"
        />
      </div>

      <div class="auth-form-input">
        <label for="email">Email</label>
        <input
          v-model="email"
          type="email"
          id="email"
          name="email"
          autocomplete="email"
        />
      </div>

      <div class="auth-form-input">
        <label for="password">Password</label>
        <input
          v-model="password"
          type="password"
          name="password"
          id="password"
          autocomplete="new-password"
        />
      </div>

      <div class="auth-form-input">
        <label for="confirm-password">Confirm password</label>
        <input
          v-model="confirmPassword"
          type="password"
          id="confirm-password"
          name="confirm-password"
          autocomplete="new-password"
        />
      </div>

      <div class="actions">
        <button>Sign Up</button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { AuthActionTypes } from "../../store/modules/auth/action-types";
import { AuthMutationTypes } from "../../store/modules/auth/mutation-types";

export default defineComponent({
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