<template>
  <div>
    <form action="/api/login" method="post" @submit="onFormSubmit">
      <div class="auth-form-input">
        <label for="email">Email</label>
        <input
          v-model="email"
          type="email"
          id="email"
          name="email"
          autocomplete="email"
          required
        />
      </div>

      <div class="auth-form-input">
        <label for="password">Password</label>
        <input
          v-model="password"
          type="password"
          name="password"
          id="password"
          autocomplete="current-password"
          required
        />
      </div>

      <div class="actions">
        <button>Login</button>
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
  data() {
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
    password: {
      get() {
        return this.$store.getters.AUTH_FORM__password;
      },
      set(value: string) {
        this.$store.commit(AuthMutationTypes.FORM_PASSWORD, value);
      },
    },
  },
  methods: {
    onFormSubmit(e: any) {
      e.preventDefault();
      this.$store.dispatch(AuthActionTypes.LOGIN);
    },
  },
});
</script>

<style scoped>
</style>