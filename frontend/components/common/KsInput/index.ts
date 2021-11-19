import { defineComponent, h, VNode, reactive, ref, PropType } from "vue";

// Components
import Tooltip from "../Tooltip";

// Libraries
import ClipboardJS from "clipboard";

// Styles
import "./index.scss";

// Types
export type InputType = "text" | "password" | "copy" | "number" | "email";

export default defineComponent({
  emits: ["update:modelValue", "focus", "blur", "keyup:enter"],
  render() {
    return h(
      "div",
      {
        ref: "ksInputContainer",
        class: ["ks-input", { error: this.error }, { password: this.type == 'password' }, { visible: this.type == 'password' && this.isPasswordVisible }],
      },
      [
        h(
          "label",
          { class: ["ks-input-container"] },
          [
            this.renderInput(),
            this.renderPlaceholder()
          ]
        ),
        this.getButtonCTX(),
      ]
    );
  },
  setup() {
    const input = ref<HTMLInputElement>();
    const btnCopy = ref<HTMLElement>();
    const ksInputContainer = ref<HTMLDivElement>()
    return {
      ksInputContainer,
      input,
      btnCopy,
    };
  },
  data() {
    return {
      clipboardJS: null as ClipboardJS | null,
      isCopySupported: false,
      dataInput: this.modelValue ?? this.value ?? "",
      inputType: this.type ?? 'text',
      isFocusInput: false,
      isPasswordVisible: false
    };
  },
  props: {
    type: {
      type: String as PropType<InputType>,
      default: "text",
      required: false
    },
    value: {
      type: [String, Number] as PropType<string | number>,
      default: '',
      required: false
    },
    modelValue: {
      type: [String, Number] as PropType<string | number>,
      default: '',
      required: false
    },
    placeHolder: {
      type: [String, Number] as PropType<string | number>,
      default: undefined,
      required: false
    },
    autoFocus: {
      type: Boolean,
      default: false,
    },
    error: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    renderInput() {

      const inputProps = Object.assign({}, { ...this.$attrs })
      delete inputProps.class;
      return h("input", {
        ref: "input",
        type: this.getInputType(),
        class: [this.getInputClass()],
        value: this.dataInput,
        readonly: this.type == "copy",
        "copy-supported": this.isCopySupported,
        oninput: (e: Event) => {
          this.dataInput = (e.target as HTMLInputElement).value;
          this.$emit("update:modelValue", (e.target as HTMLInputElement).value);
        },
        onfocus: this.onFocusInput,
        onblur: this.onBlurInput,
        onkeyup: this.onKeyUp,
        ...inputProps,
      });
    },

    renderPlaceholder() {
      if (this.placeHolder)
        return h("span", {
          class: ["ks-input-placeholder", { "inborder": this.dataInput || this.isFocusInput }],
        }, this.placeHolder);

      return null;
    },
    // render boutton password toggle
    renderBtnPwdToggle() {
      const state = reactive({
        pwdVisible: false,
      });
      const togglePasswordView = () => {
        this.isPasswordVisible = !this.isPasswordVisible;
        if (this.input)
          if (this.isPasswordVisible) {
            this.input.type = "text";
          } else {
            this.input.type = "password";
          }
      };

      const eye = (() => {
        const path = h("path", { fill: "currentColor", d: "M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z" })
        const svg = h("svg", { class: ["ks-button-eye"], xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 576 512", fill: "none" }, path)
        return svg
      })();

      const eyeSlach = (() => {
        const path = h("path", { fill: "currentColor", d: "M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z" })
        const svg = h("svg", { class: ["ks-button-eye-slash"], xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 640 512", fill: "none" }, path)
        return svg
      })();

      return h("span", {
        class: ["toggle-password-view"],
        onmousedown: (e: Event) => e.preventDefault(),
        onmouseup: (e: Event) => e.preventDefault(),
        onclick: togglePasswordView,
      }, [eyeSlach, eye]);
    },
    // render boutton copy
    renderBtnCopy() {
      this.isCopySupported = ClipboardJS.isSupported();
      if (this.isCopySupported)
        return h(
          Tooltip,
          {
            trigger: "click",
            clickToShow: true,
            duration: 2000,
          },
          {
            content: () => "Copie!",
            default: () =>
              h("span", {
                ref: "btnCopy",
                class: ["clipboard-btn"],
                "data-clipboard-text": this.modelValue ?? this.value ?? "",
              }),
          }
        );
    },
    onFocusInput(e: Event) {
      this.isFocusInput = true
      this.$emit("focus", e);
      if (this.type == "copy" && this.input) this.input.select();
    },
    onBlurInput(e: Event) {
      this.isFocusInput = false;
      this.$emit("blur", e);
    },
    onKeyUp(e: KeyboardEvent) {
      if (e.key === "Enter" || e.keyCode === 13) {
        this.$emit("keyup:enter", e);
      }
    },
    getInputClass() {
      if (this.type == "password") return "ks-input-password";
      else if (this.type == "copy") return "ks-input-copy";
      else null;
    },
    getInputType() {
      if (this.type == "password") return "password";
      else if (this.type == "number") return "number";
      else if (this.type == "email") return "email";
      else "text";
    },
    getInputDefaultPlaceHolder() {
      if (this.type == "text") return "Champ de saisie";
      else if (this.type == "password") return "Mot de passe";
      else null;
    },
    getButtonCTX() {
      if (this.type == "password") return this.renderBtnPwdToggle();
      else if (this.type == "copy") return this.renderBtnCopy();
      else null;
    },
  },
  mounted() {
    if (this.type == "copy" && this.btnCopy && this.isCopySupported) {
      this.clipboardJS = new ClipboardJS(this.btnCopy);
    }

    if (this.autoFocus && this.input) {
      this.input.focus();
    }
  },
  beforeUnmount() {
    if (this.isCopySupported) this.clipboardJS?.destroy();
  },
});