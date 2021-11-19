<template>
  <form action="/api/login" method="post" @submit="onFormSubmit">
    <KSInputText
      class="auth-form-input"
      title="Email"
      autocomplete="email"
      v-model="email"
      :type="email"
      :placeHolder="'Email'"
    />

    <KSInputText
      class="auth-form-input"
      title="Mot de passe"
      autocomplete="current-password"
      v-model="password"
      :type="'password'"
      :placeHolder="'Mot de passe'"
    />

    <div class="actions">
      <button>Se connecter</button>
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
      console.log(this.email, this.password);
      this.$store.dispatch(AuthActionTypes.LOGIN);
    },
  },
});
</script>

<style scoped>
</style>