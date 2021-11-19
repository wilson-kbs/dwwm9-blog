<template>
  <div class="modal">
    <div v-if="active" class="modal-wrapper">
      <div class="modal-mask" @click.self="showModal(false)"></div>
      <div
        class="
          modal-content-wrapper
          lg:container lg:my-20 lg:h-5/6
          w-screen
          h-full
          flex flex-col
        "
      >
        <span class="close-modal">X</span>
        <div class="modal-content">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { AppMutationTypes } from "../../store/modules/app/mutation-types";

export default defineComponent({
  emits: ["close"],
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },
  setup() {
    return {};
  },

  data() {
    this.$store.commit(AppMutationTypes.IS_MODAL_ACTIVE, this.show);
    return {
      active: false,
    };
  },
  watch: {
    show(value) {
      this.showModal(value);
    },
  },
  methods: {
    showModal(value: boolean) {
      this.$store.commit(AppMutationTypes.IS_MODAL_ACTIVE, value);
      this.active = value;
      // if (value) document.querySelector("body").style.overflowY = "hidden";
      // else document.querySelector("body").style.overflowY = "overlay";
      if (value) {
        document.querySelector("body").classList.add("noscroll");
      } else {
        this.$emit("close");
        document.querySelector("body").classList.remove("noscroll");
      }
    },
  },
  mounted() {
    this.showModal(this.show);
  },
});
</script>

<style scoped>
.modal {
  position: fixed;
  z-index: 100;
  top: 0;
  left: 0;
}

.modal-wrapper {
  position: relative;
  display: flex;
  justify-content: center;
  height: 100vh;
  width: 100vw;
}

.modal-mask {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.419);
}

.modal-content-wrapper {
  align-self: center;
  /* margin: 5rem; */
  z-index: 101;
  max-height: 100vh;
  overflow-y: scroll;
  overflow-x: hidden;
  background-color: white;
  padding: 2rem 2rem;
  border-radius: 1rem;
}

.close-modal {
  font-size: 2vmin;
}
</style>

<style lang="scss">
.noscroll {
  overflow: hidden;
}
</style>